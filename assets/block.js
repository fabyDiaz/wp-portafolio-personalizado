(function (blocks, element, editor, components, i18n) {
    var el = element.createElement;
    var registerBlockType = blocks.registerBlockType;
    var InspectorControls = editor.InspectorControls;
    var PanelBody = components.PanelBody;
    var RangeControl = components.RangeControl;
    var TextControl = components.TextControl;
    var ServerSideRender = components.ServerSideRender || wp.serverSideRender;
    var __ = i18n.__;

    registerBlockType('portfolio-personalizado/proyectos', {
        title: __('Portfolio Proyectos', 'portfolio-personalizado'),
        icon: 'portfolio',
        category: 'widgets',
        attributes: {
            columns: {
                type: 'number',
                default: 3
            },
            limit: {
                type: 'number',
                default: -1
            },
            category: {
                type: 'string',
                default: ''
            }
        },

        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            return [
                el(InspectorControls, {},
                    el(PanelBody, { title: __('Configuración del Portfolio', 'portfolio-personalizado'), initialOpen: true },
                        el(RangeControl, {
                            label: __('Número de Columnas', 'portfolio-personalizado'),
                            value: attributes.columns,
                            onChange: function (value) {
                                setAttributes({ columns: value });
                            },
                            min: 2,
                            max: 4
                        }),
                        el(RangeControl, {
                            label: __('Límite de Proyectos (-1 = todos)', 'portfolio-personalizado'),
                            value: attributes.limit,
                            onChange: function (value) {
                                setAttributes({ limit: value });
                            },
                            min: -1,
                            max: 20
                        }),
                        el(TextControl, {
                            label: __('Filtrar por Categoría (slug)', 'portfolio-personalizado'),
                            value: attributes.category,
                            onChange: function (value) {
                                setAttributes({ category: value });
                            },
                            help: __('Deja vacío para mostrar todas las categorías', 'portfolio-personalizado')
                        })
                    )
                ),
                el('div', { className: 'portfolio-block-preview' },
                    el(ServerSideRender, {
                        block: 'portfolio-personalizado/proyectos',
                        attributes: attributes
                    })
                )
            ];
        },

        save: function () {
            return null; // Renderizado en el servidor
        }
    });
})(
    window.wp.blocks,
    window.wp.element,
    window.wp.blockEditor || window.wp.editor,
    window.wp.components,
    window.wp.i18n
);