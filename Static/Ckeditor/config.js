/**
 * @license Copyright (c) 2003-2023, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function (config) {
    // Define changes to default configuration here.
    // For complete reference see:
    // https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_config.html

    // The toolbar groups arrangement, optimized for a single toolbar row.
    config.toolbarGroups = [
        { name: 'document',    groups: ['mode', 'document', 'doctools'] },
        { name: 'clipboard',   groups: ['clipboard', 'undo'] },
        { name: 'editing',     groups: ['find', 'selection', 'spellchecker'] },
        { name: 'forms' },
        { name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
        { name: 'paragraph',   groups: ['list', 'indent', 'blocks', 'align', 'bidi'] },
        { name: 'links' },
        { name: 'insert' },
        { name: 'styles' },
        { name: 'colors' },
        { name: 'tools' },
        { name: 'others' },
        { name: 'about' }
    ];

    config.language = 'pt-br';

    // Bundled plugins a serem desabilitados. `autosave` nunca foi efetivamente
    // utilizado e sumiu do build nativo na 4.22.1, mas a diretiva segue
    // inofensiva caso seja adicionado via extraPlugins no futuro.
    config.removePlugins = 'autosave';

    // Plugins externos (nao fazem parte do pacote Full do CKEditor 4.22.1).
    // `base64image` e necessario para as Noticias e demais editores que expoem
    // o botao `base64image` nos toolbars "PADRAO/COMPLETA/BASICA" definidos em
    // Lib/Pixel/Form/FormInputTextArea.php.
    config.extraPlugins = 'base64image';

    // Desabilita o tab "Advanced" no dialog de Link (comportamento antigo).
    config.removeDialogTabs = 'link:advanced';

    // Evita a chamada periodica a cke4.ckeditor.com (introduzida em 4.22)
    // que gera o toast de "versao desatualizada" no topo do editor.
    config.versionCheck = false;
};
