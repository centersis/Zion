/**
 * PixelMaskAdapter.js
 * Adapter para IMask.js mantendo compatibilidade com jquery.mask
 * 
 * Este adapter permite que o código existente continue funcionando sem alterações,
 * convertendo as chamadas antigas para a nova biblioteca IMask.js
 * 
 * Nota: jquery.maskMoney continua usando a biblioteca original (não é convertida)
 * 
 * @version 1.2.0
 * @requires IMask.js v7.x
 * @requires jQuery
 */

(function($) {
    'use strict';

    // Detectar se é dispositivo mobile
    var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || 
                   (window.innerWidth <= 768 && 'ontouchstart' in window);

    // Armazenar instâncias do IMask para cada elemento
    var maskInstances = new WeakMap();

    /**
     * Verifica se o elemento é um campo de upload (file input)
     * Campos de upload não podem ter máscara aplicada
     * @param {HTMLElement} element - Elemento a verificar
     * @returns {boolean} - true se for campo de upload
     */
    function isFileInput(element) {
        if (!element || element.tagName !== 'INPUT') {
            return false;
        }
        return element.type === 'file' || element.type === 'FILE';
    }

    /**
     * Verifica se o elemento é válido para receber máscara
     * Apenas input[type="text"] e input[type="tel"] podem receber máscara
     * @param {HTMLElement} element - Elemento a verificar
     * @returns {boolean} - true se for um campo válido para máscara
     */
    function isValidMaskElement(element) {
        if (!element) {
            return false;
        }
        
        // Apenas INPUT é válido (textarea é rejeitado)
        if (element.tagName !== 'INPUT') {
            return false;
        }
        
        // Apenas type="text" ou type="tel" são válidos
        var type = (element.type || '').toLowerCase();
        return type === 'text' || type === 'tel';
    }

    /**
     * Converte padrão jquery.mask para IMask
     * @param {string} pattern - Padrão no formato jquery.mask (ex: "999.999.999-99")
     * @returns {object} - Configuração IMask
     */
    function convertPatternToIMask(pattern) {
        // Mapeamento de caracteres jquery.mask para IMask
        var imaskPattern = pattern
            .replace(/9/g, '0')  // 9 (número) -> 0 (número no IMask)
            .replace(/\?/g, '');  // ? (opcional) -> removido, tratado separadamente

        return {
            mask: imaskPattern,
            lazy: true, // Sempre lazy inicialmente para não mostrar placeholders quando vazio
            placeholderChar: '', // Sem placeholder char para não mostrar caracteres quando vazio
            autofix: true, // Corrige valores automaticamente
            overwrite: true, // Sobrescreve caracteres ao digitar
            prepare: function (str) {
                // Remove caracteres não numéricos para máscaras numéricas
                return str.replace(/[^\d]/g, '');
            }
        };
    }

    /**
     * Obtém configuração específica para tipos conhecidos
     * @param {string} pattern - Padrão da máscara
     * @returns {object|null} - Configuração otimizada ou null
     */
    function getOptimizedMaskConfig(pattern) {
        var configs = {
            // CPF: 999.999.999-99
            '999.999.999-99': {
                mask: '000.000.000-00',
                lazy: true, // Sempre lazy inicialmente para não mostrar placeholders
                autofix: true,
                overwrite: true,
                placeholderChar: '', // Sem placeholder char
                prepare: function (str) {
                    return str.replace(/[^\d]/g, '');
                }
            },
            // CNPJ: 99.999.999/9999-99
            '99.999.999/9999-99': {
                mask: '00.000.000/0000-00',
                lazy: true, // Sempre lazy inicialmente para não mostrar placeholders
                autofix: true,
                overwrite: true,
                placeholderChar: '', // Sem placeholder char
                prepare: function (str) {
                    return str.replace(/[^\d]/g, '');
                }
            },
            // CEP: 99.999-999
            '99.999-999': {
                mask: '00.000-000',
                lazy: true, // Sempre lazy inicialmente para não mostrar placeholders
                autofix: true,
                overwrite: true,
                placeholderChar: '', // Sem placeholder char
                prepare: function (str) {
                    return str.replace(/[^\d]/g, '');
                }
            },
            // Telefone com 9º dígito opcional: (99) ?99999-9999
            '(99) ?99999-9999': {
                mask: [
                    { 
                        mask: '(00) 0000-0000',
                        lazy: true, // Sempre lazy inicialmente para não mostrar placeholders
                        autofix: true,
                        overwrite: true,
                        placeholderChar: '', // Sem placeholder char
                        prepare: function (str) {
                            return str.replace(/[^\d]/g, '');
                        }
                    },
                    { 
                        mask: '(00) 00000-0000',
                        lazy: true, // Sempre lazy inicialmente para não mostrar placeholders
                        autofix: true,
                        overwrite: true,
                        placeholderChar: '', // Sem placeholder char
                        prepare: function (str) {
                            return str.replace(/[^\d]/g, '');
                        }
                    }
                ]
            },
            // Data: 99/99/9999 (simplificado para evitar problemas com IMask.MaskedRange)
            '99/99/9999': {
                mask: '00/00/0000',
                lazy: true, // Sempre lazy inicialmente para não mostrar placeholders
                autofix: true,
                overwrite: true,
                placeholderChar: '', // Sem placeholder char
                prepare: function (str) {
                    return str.replace(/[^\d]/g, '');
                }
            },
            // DateTime: 99/99/9999 99:99
            '99/99/9999 99:99': {
                mask: '00/00/0000 00:00'
            },
            // Time: 99:99
            '99:99': {
                mask: '00:00'
            },
            // Time com segundos: 99:99:99
            '99:99:99': {
                mask: '00:00:00'
            }
        };

        return configs[pattern] || null;
    }

    /**
     * Plugin jQuery para aplicar máscara usando IMask
     * Mantém compatibilidade com $.fn.mask()
     */
    $.fn.mask = function(pattern, options) {
        options = options || {};
        
        // Verifica se IMask está disponível
        if (typeof IMask === 'undefined') {
            console.error('PixelMaskAdapter: IMask.js não foi carregado! Não é possível aplicar a máscara.');
            return this;
        }

        return this.each(function() {
            var element = this;
            var $element = $(element);

            // Não aplica máscara em campos de upload (file input)
            if (isFileInput(element)) {
                return;
            }

            // Apenas aplica máscara em campos válidos (input[type="text"], input[type="tel"] ou textarea)
            if (!isValidMaskElement(element)) {
                return;
            }

            // Remove máscara anterior se existir
            if (maskInstances.has(element)) {
                maskInstances.get(element).destroy();
            }

            // Salva valor original antes de qualquer alteração
            var valorOriginal = element.value || '';
            var isPlaceholder = valorOriginal && (valorOriginal.includes('__') || valorOriginal.match(/^[_\s\-\(\)\/\.]+$/));

            // Tenta usar configuração otimizada
            var maskConfig = getOptimizedMaskConfig(pattern);
            
            // Se não houver configuração otimizada, converte o padrão
            if (!maskConfig) {
                maskConfig = convertPatternToIMask(pattern);
            }

            // Valida se maskConfig é válido
            if (!maskConfig || typeof maskConfig !== 'object') {
                console.error('Configuração de máscara inválida para pattern:', pattern);
                return;
            }
            
            // Valida se mask está presente (pode ser string ou array)
            if (!maskConfig.mask || (typeof maskConfig.mask !== 'string' && !Array.isArray(maskConfig.mask))) {
                console.error('Configuração de máscara sem propriedade mask válida para pattern:', pattern, 'mask:', maskConfig.mask);
                return;
            }

            // Mescla opções customizadas
            if (options.reverse) {
                maskConfig.reverse = true;
            }

            // Configurações adicionais para mobile (não sobrescreve se já definido)
            if (isMobile) {
                if (maskConfig.lazy === undefined) {
                    maskConfig.lazy = true;
                }
                if (maskConfig.autofix === undefined) {
                    maskConfig.autofix = true;
                }
                if (maskConfig.overwrite === undefined) {
                    maskConfig.overwrite = true;
                }
            } else {
                // Desktop: lazy false para formatação em tempo real
                if (maskConfig.lazy === undefined) {
                    maskConfig.lazy = false;
                }
            }

            // Adiciona atributos mobile-friendly ao input
            if (isMobile && pattern.match(/^\d+[\d\.\-\/\(\)\s]+$/)) {
                // Se for máscara numérica, adiciona inputmode
                element.setAttribute('inputmode', 'numeric');
                if (element.type === 'text') {
                    element.type = 'tel'; // type="tel" abre teclado numérico em mobile
                }
            }

            // Configura para não mostrar placeholders quando campo está vazio
            // Isso evita que apareçam caracteres como "___" ou "__/__/____" quando o campo está vazio
            if (!maskConfig.lazy && (!valorOriginal || valorOriginal.trim() === '' || isPlaceholder)) {
                // Se campo está vazio, força lazy para não mostrar placeholders
                maskConfig.lazy = true;
            }
            
            // Garante que placeholderChar está vazio para não mostrar caracteres de placeholder
            if (maskConfig.placeholderChar === undefined || maskConfig.placeholderChar === '_') {
                maskConfig.placeholderChar = '';
            }
            
            // Cria instância do IMask ANTES de alterar qualquer valor
            try {
                var maskInstance = IMask(element, maskConfig);
                
                if (!maskInstance) {
                    console.error('Falha ao criar instância IMask para:', element.id || element.name, 'Pattern:', pattern);
                    return;
                }
                
                maskInstances.set(element, maskInstance);
                
                // Agora sincroniza o valor: limpa placeholder ou restaura valor válido
                if (isPlaceholder || !valorOriginal || valorOriginal.trim() === '') {
                    // Limpa campo vazio ou com placeholder - não mostra placeholders
                    element.value = '';
                    maskInstance.value = '';
                    // Mantém lazy ativo para não mostrar placeholders
                    maskInstance.lazy = true;
                } else if (valorOriginal && valorOriginal.trim() !== '') {
                    // Restaura valor válido e formata
                    element.value = valorOriginal;
                    // Desativa lazy para mostrar formatação quando há valor
                    maskInstance.lazy = false;
                    maskInstance.updateValue();
                }
                
                // Sincroniza em eventos que podem alterar o valor externamente
                // Isso resolve o warning "Element value was changed outside of mask"
                var syncMask = function() {
                    if (maskInstances.has(element)) {
                        try {
                            maskInstance.updateValue();
                        } catch (e) {
                            // Ignora erros de sincronização
                        }
                    }
                };
                
                // Sincroniza quando o campo ganha foco (principal causa do warning)
                element.addEventListener('focus', function() {
                    // Quando ganha foco, desativa lazy para mostrar formatação
                    if (maskInstance.lazy) {
                        maskInstance.lazy = false;
                        // Se campo está vazio, mostra placeholder ao focar
                        if (!element.value || element.value.trim() === '') {
                            maskInstance.updateValue();
                        }
                    }
                    syncMask();
                }, true);
                
                // Sincroniza quando o campo perde foco
                element.addEventListener('blur', function() {
                    syncMask();
                    // Se campo ficou vazio após perder foco, remove placeholders
                    if (!element.value || element.value.trim() === '' || element.value.match(/^[_\s\-\(\)\/\.]+$/)) {
                        element.value = '';
                        maskInstance.value = '';
                        // Reativa lazy para não mostrar placeholders quando vazio
                        maskInstance.lazy = true;
                    }
                }, true);
                
                // Sincroniza após qualquer mudança no valor (usando input event)
                element.addEventListener('input', function() {
                    // Atualiza após um pequeno delay para garantir que o valor foi processado
                    setTimeout(syncMask, 0);
                }, true);

                // Mantém compatibilidade com eventos
                if (options.onChange || options.onKeyPress || options.onComplete) {
                    maskInstance.on('accept', function() {
                        if (options.onChange) {
                            options.onChange(maskInstance.value, null, element);
                        }
                    });

                    maskInstance.on('complete', function() {
                        if (options.onComplete) {
                            options.onComplete(maskInstance.value);
                        }
                    });
                }
            } catch (e) {
                console.error('Erro ao aplicar máscara IMask:', e);
            }
        });
    };

    // maskMoney não é convertido - continua usando a biblioteca original jquery.maskMoney
    // A biblioteca original será carregada antes deste adapter e não será sobrescrita

    /**
     * Método para remover máscara
     * Mantém compatibilidade com $.fn.unmask()
     */
    $.fn.unmask = function() {
        return this.each(function() {
            if (maskInstances.has(this)) {
                maskInstances.get(this).destroy();
                maskInstances.delete(this);
            }
        });
    };

    /**
     * Método para obter valor não mascarado
     * Mantém compatibilidade com $.fn.cleanVal()
     */
    $.fn.cleanVal = function() {
        if (maskInstances.has(this[0])) {
            return maskInstances.get(this[0]).unmaskedValue;
        }
        return this.val();
    };

    // Garantir que o adapter seja aplicado mesmo se bibliotecas antigas carregarem depois
    // Sobrescreve novamente após um pequeno delay para garantir precedência
    var adapterMask = $.fn.mask;
    
    var protectAdapter = function() {
        // Verifica periodicamente se a função foi sobrescrita
        setInterval(function() {
            // Se a função foi sobrescrita, restaura o adapter
            if ($.fn.mask !== adapterMask) {
                $.fn.mask = adapterMask;
            }
            // maskMoney não é protegido - usa a biblioteca original
        }, 300);
    };
    
    // Inicia proteção imediatamente e após delays
    protectAdapter();
    setTimeout(protectAdapter, 100);
    setTimeout(protectAdapter, 500);
    setTimeout(protectAdapter, 1000);
    
    // Intercepta eval para garantir que máscaras sejam aplicadas após execução
    // Isso é necessário porque o MasterDetail usa eval() para executar JavaScript inline
    if (typeof window.eval === 'function' && window.eval.toString().indexOf('[native code]') !== -1) {
        var originalEval = window.eval;
        window.eval = function(code) {
            var result = originalEval.call(this, code);
            // Após eval, aguarda um pouco e reaplica máscaras se necessário
            // Isso garante que máscaras aplicadas via JavaScript inline sejam processadas
            setTimeout(function() {
                if (typeof window.reapplyMasks === 'function') {
                    window.reapplyMasks();
                }
            }, 100);
            return result;
        };
    }

    // Aplicar máscaras em elementos que já existem no DOM
    $(document).ready(function() {
        // Reaplica máscaras em elementos que podem ter sido criados antes do adapter
        $('[data-mask]').each(function() {
            // Não aplica máscara em campos de upload ou campos inválidos
            if (isFileInput(this) || !isValidMaskElement(this)) {
                return;
            }
            var pattern = $(this).data('mask');
            if (pattern) {
                $(this).mask(pattern);
            }
        });
    });

    // Função para reaplicar máscaras em elementos recém-adicionados
    function reapplyMasksInContainer(container) {
        if (!container) return;
        
        var $container = $(container);
        
        // Aguarda um pequeno delay para garantir que scripts inline foram executados
        setTimeout(function() {
            // Procura por inputs que podem precisar de máscara
            $container.find('input, textarea').each(function() {
                var $element = $(this);
                var element = this;
                
                // Não aplica máscara em campos de upload ou campos inválidos
                if (isFileInput(element) || !isValidMaskElement(element)) {
                    return;
                }
                
                // Se já tem instância de máscara, ignora
                if (maskInstances.has(element)) {
                    return;
                }
                
                // Verifica se há atributo data-mask
                var dataMask = $element.data('mask');
                if (dataMask) {
                    $element.mask(dataMask);
                    return;
                }
                
                // Verifica se o elemento tem ID que sugere máscara (telefone, cpf, cnpj, cep, data)
                var elementId = element.id || '';
                var elementName = element.name || '';
                var idOrName = (elementId + ' ' + elementName).toLowerCase();
                
                if (idOrName.match(/telefone|phone|celular/)) {
                    $element.mask('(99) ?99999-9999');
                } else if (idOrName.match(/cpf/)) {
                    $element.mask('999.999.999-99');
                } else if (idOrName.match(/cnpj/)) {
                    $element.mask('99.999.999/9999-99');
                } else if (idOrName.match(/cep/)) {
                    $element.mask('99.999-999');
                } else if (idOrName.match(/data|date/) && !idOrName.match(/hora|time/)) {
                    $element.mask('99/99/9999');
                }
            });
        }, 150);
    }

    // Observer para detectar quando novos elementos são adicionados ao DOM (via AJAX)
    var maskObserver = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // Element node
                        reapplyMasksInContainer(node);
                    }
                });
            }
        });
    });

    // Inicia observação quando o DOM estiver pronto
    $(document).ready(function() {
        maskObserver.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
    
    // Expõe função global para reaplicar máscaras manualmente após AJAX
    window.reapplyMasks = function(container) {
        reapplyMasksInContainer(container || document.body);
    };

})(jQuery);

