/**
 * PixelMaskAdapter.js
 * Adapter para IMask.js mantendo compatibilidade com jquery.mask e jquery.maskMoney
 * 
 * Este adapter permite que o código existente continue funcionando sem alterações,
 * convertendo as chamadas antigas para a nova biblioteca IMask.js
 * 
 * @version 1.0.0
 * @requires IMask.js v7.x
 * @requires jQuery
 */

(function($) {
    'use strict';

    // Armazenar instâncias do IMask para cada elemento
    var maskInstances = new WeakMap();

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
            lazy: false,
            placeholderChar: '_'
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
                mask: '000.000.000-00'
            },
            // CNPJ: 99.999.999/9999-99
            '99.999.999/9999-99': {
                mask: '00.000.000/0000-00'
            },
            // CEP: 99.999-999
            '99.999-999': {
                mask: '00.000-000'
            },
            // Telefone com 9º dígito opcional: (99) ?99999-9999
            '(99) ?99999-9999': {
                mask: [
                    { mask: '(00) 0000-0000' },
                    { mask: '(00) 00000-0000' }
                ]
            },
            // Data: 99/99/9999
            '99/99/9999': {
                mask: Date,
                pattern: 'd{/}`m{/}`Y',
                blocks: {
                    d: {
                        mask: IMask.MaskedRange,
                        from: 1,
                        to: 31,
                        maxLength: 2
                    },
                    m: {
                        mask: IMask.MaskedRange,
                        from: 1,
                        to: 12,
                        maxLength: 2
                    },
                    Y: {
                        mask: IMask.MaskedRange,
                        from: 1900,
                        to: 2999
                    }
                },
                format: function (date) {
                    var day = date.getDate();
                    var month = date.getMonth() + 1;
                    var year = date.getFullYear();
                    return [
                        ('0' + day).slice(-2),
                        ('0' + month).slice(-2),
                        year
                    ].join('/');
                },
                parse: function (str) {
                    var parts = str.split('/');
                    return new Date(parts[2], parts[1] - 1, parts[0]);
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

        return this.each(function() {
            var element = this;
            var $element = $(element);

            // Remove máscara anterior se existir
            if (maskInstances.has(element)) {
                maskInstances.get(element).destroy();
            }

            // Tenta usar configuração otimizada
            var maskConfig = getOptimizedMaskConfig(pattern);
            
            // Se não houver configuração otimizada, converte o padrão
            if (!maskConfig) {
                maskConfig = convertPatternToIMask(pattern);
            }

            // Mescla opções customizadas
            if (options.reverse) {
                maskConfig.reverse = true;
            }

            // Cria instância do IMask
            try {
                var maskInstance = IMask(element, maskConfig);
                maskInstances.set(element, maskInstance);

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
                console.log('Pattern:', pattern, 'Config:', maskConfig);
            }
        });
    };

    /**
     * Plugin jQuery para máscara monetária usando IMask
     * Mantém compatibilidade com $.fn.maskMoney()
     */
    $.fn.maskMoney = function(options) {
        options = options || {};

        var defaults = {
            prefix: 'R$ ',
            suffix: '',
            thousands: '.',
            decimal: ',',
            precision: 2,
            allowZero: true,
            allowNegative: false,
            allowEmpty: false,
            affixesStay: true
        };

        var settings = $.extend({}, defaults, options);

        return this.each(function() {
            var element = this;
            var $element = $(element);

            // Remove máscara anterior se existir
            if (maskInstances.has(element)) {
                maskInstances.get(element).destroy();
            }

            // Configuração IMask para valores monetários
            var maskConfig = {
                mask: Number,
                scale: settings.precision,
                thousandsSeparator: settings.thousands,
                radix: settings.decimal,
                mapToRadix: ['.'],
                min: settings.allowNegative ? undefined : 0,
                normalizeZeros: true,
                padFractionalZeros: true
            };

            // Adiciona prefixo/sufixo se configurado
            if (settings.prefix || settings.suffix) {
                var maskPattern = '';
                if (settings.prefix) maskPattern += settings.prefix;
                maskPattern += 'num';
                if (settings.suffix) maskPattern += settings.suffix;

                maskConfig = {
                    mask: maskPattern,
                    blocks: {
                        num: {
                            mask: Number,
                            scale: settings.precision,
                            thousandsSeparator: settings.thousands,
                            radix: settings.decimal,
                            mapToRadix: ['.'],
                            min: settings.allowNegative ? undefined : 0,
                            normalizeZeros: true,
                            padFractionalZeros: true
                        }
                    }
                };
            }

            // Cria instância do IMask
            try {
                var maskInstance = IMask(element, maskConfig);
                maskInstances.set(element, maskInstance);

                // Configura valor inicial se houver
                if ($element.val()) {
                    maskInstance.value = $element.val();
                }
            } catch (e) {
                console.error('Erro ao aplicar maskMoney:', e);
            }
        });
    };

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

    // Log de inicialização
    if (typeof console !== 'undefined' && console.log) {
        console.log('PixelMaskAdapter inicializado com IMask.js');
    }

})(jQuery);

