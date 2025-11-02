<?php
/**
 * Plugin Name: Portfolio Personalizado
 * Plugin URI: https://github.com/tuusuario/portfolio-personalizado
 * Description: Plugin para crear un portfolio personalizado con opciones de customización de colores y etiquetas.
 * Version: 2.1.0
 * Author: Fabiola Díaz
 * Author URI: https://fabydev.cl
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: portfolio-personalizado
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.2
 */

// Seguridad: prevenir acceso directo
if (!defined('ABSPATH')) {
    exit;
}

// Definir constantes
define('PORTFOLIO_PERSONALIZADO_VERSION', '2.1.0');
define('PORTFOLIO_PERSONALIZADO_PATH', plugin_dir_path(__FILE__));
define('PORTFOLIO_PERSONALIZADO_URL', plugin_dir_url(__FILE__));

class PortfolioPersonalizado {
    
    private $opciones;
    
    public function __construct() {
        // Cargar opciones
        $guardadas = get_option('portfolio_personalizado_opciones', array());
        $this->opciones = array_merge($this->get_default_options(), (array) $guardadas);
        
        // Registro de Custom Post Type y taxonomía
        add_action('init', array($this, 'crear_post_type_proyectos'));
        add_action('init', array($this, 'crear_taxonomia_tecnologias'));

        // Estilos y scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

        // Campos personalizados
        add_action('add_meta_boxes', array($this, 'agregar_meta_boxes'));
        add_action('save_post', array($this, 'guardar_campos_personalizados'));

        // Shortcode
        add_shortcode('mostrar_proyectos', array($this, 'mostrar_proyectos_shortcode'));
        
        // Bloque de Gutenberg
        add_action('init', array($this, 'registrar_bloque_gutenberg'));
        
        // Menú de configuración
        add_action('admin_menu', array($this, 'agregar_menu_configuracion'));
        add_action('admin_init', array($this, 'registrar_configuraciones'));
    }
    
    /**
     * Opciones por defecto
     */
    private function get_default_options() {
        return array(
            'color_primario' => '#6c5ce7',
            'color_secundario' => '#a29bfe',
            'color_acento' => '#fd79a8',
            'color_fondo_card' => '#ffffff',
            'color_texto_card' => '#2d3436',
            'color_fondo_imagen' => '#6c5ce7',
            'color_fondo_imagen_secundario' => '#9f5efb',
            'color_boton' => '#6c5ce7',
            'color_boton_hover' => '#a29bfe',
            'color_texto_boton' => '#ffffff',
            'border_radius_card' => '20',
            'border_radius_boton' => '10',
            'tags_personalizados' => array()
        );
    }
    
    /**
     * Crear Custom Post Type
     */
    public function crear_post_type_proyectos() {
        $labels = array(
            'name' => __('Proyectos', 'portfolio-personalizado'),
            'singular_name' => __('Proyecto', 'portfolio-personalizado'),
            'menu_name' => __('Mis Proyectos', 'portfolio-personalizado'),
            'add_new' => __('Añadir Proyecto', 'portfolio-personalizado'),
            'add_new_item' => __('Añadir Nuevo Proyecto', 'portfolio-personalizado'),
            'edit_item' => __('Editar Proyecto', 'portfolio-personalizado'),
            'new_item' => __('Nuevo Proyecto', 'portfolio-personalizado'),
            'view_item' => __('Ver Proyecto', 'portfolio-personalizado'),
            'search_items' => __('Buscar Proyectos', 'portfolio-personalizado'),
            'not_found' => __('No se encontraron proyectos', 'portfolio-personalizado'),
            'not_found_in_trash' => __('No hay proyectos en la papelera', 'portfolio-personalizado')
        );
        
        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-portfolio',
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'rewrite' => array('slug' => 'proyectos'),
            'show_in_rest' => true
        );
        
        register_post_type('proyecto', $args);
    }
    
    /**
     * Crear taxonomía personalizada
     */
    public function crear_taxonomia_tecnologias() {
        $labels = array(
            'name' => __('Tecnologías', 'portfolio-personalizado'),
            'singular_name' => __('Tecnología', 'portfolio-personalizado'),
            'menu_name' => __('Tecnologías', 'portfolio-personalizado'),
            'add_new_item' => __('Añadir Nueva Tecnología', 'portfolio-personalizado'),
            'edit_item' => __('Editar Tecnología', 'portfolio-personalizado')
        );
        
        register_taxonomy('tecnologia', 'proyecto', array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'tecnologia'),
            'show_in_rest' => true
        ));
    }
    
    /**
     * Añadir meta boxes
     */
    public function agregar_meta_boxes() {
        add_meta_box(
            'proyecto_detalles',
            __('Detalles del Proyecto', 'portfolio-personalizado'),
            array($this, 'proyecto_detalles_callback'),
            'proyecto',
            'normal',
            'high'
        );
    }
    
    /**
     * Formulario meta box
     */
    public function proyecto_detalles_callback($post) {
        wp_nonce_field(basename(__FILE__), 'proyecto_nonce');
        
        $repo_url = get_post_meta($post->ID, '_repo_url', true);
        $demo_url = get_post_meta($post->ID, '_demo_url', true);
        $icono = get_post_meta($post->ID, '_icono', true);
        $tipo_enlace = get_post_meta($post->ID, '_tipo_enlace', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="repo_url"><?php _e('URL del Repositorio', 'portfolio-personalizado'); ?></label></th>
                <td><input type="url" id="repo_url" name="repo_url" value="<?php echo esc_attr($repo_url); ?>" style="width:100%;" /></td>
            </tr>
            <tr>
                <th><label for="demo_url"><?php _e('URL de Demo', 'portfolio-personalizado'); ?></label></th>
                <td><input type="url" id="demo_url" name="demo_url" value="<?php echo esc_attr($demo_url); ?>" style="width:100%;" /></td>
            </tr>
            <tr>
                <th><label for="icono"><?php _e('Clase del Icono (Font Awesome)', 'portfolio-personalizado'); ?></label></th>
                <td>
                    <input type="text" id="icono" name="icono" value="<?php echo esc_attr($icono); ?>" placeholder="fas fa-code" style="width:100%;" />
                    <p class="description"><?php _e('Ej: fas fa-code, fas fa-mobile-alt', 'portfolio-personalizado'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="tipo_enlace"><?php _e('Tipo de Enlace', 'portfolio-personalizado'); ?></label></th>
                <td>
                    <select id="tipo_enlace" name="tipo_enlace">
                        <option value="github" <?php selected($tipo_enlace, 'github'); ?>>GitHub</option>
                        <option value="demo" <?php selected($tipo_enlace, 'demo'); ?>><?php _e('Demo en Vivo', 'portfolio-personalizado'); ?></option>
                        <option value="download" <?php selected($tipo_enlace, 'download'); ?>><?php _e('Descargar', 'portfolio-personalizado'); ?></option>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }
    
    /**
     * Guardar campos personalizados
     */
    public function guardar_campos_personalizados($post_id) {
        if (!isset($_POST['proyecto_nonce']) || !wp_verify_nonce($_POST['proyecto_nonce'], basename(__FILE__))) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;
        
        update_post_meta($post_id, '_repo_url', sanitize_url($_POST['repo_url'] ?? ''));
        update_post_meta($post_id, '_demo_url', sanitize_url($_POST['demo_url'] ?? ''));
        update_post_meta($post_id, '_icono', sanitize_text_field($_POST['icono'] ?? ''));
        update_post_meta($post_id, '_tipo_enlace', sanitize_text_field($_POST['tipo_enlace'] ?? ''));
    }
    
    /**
     * Agregar menú de configuración
     */
    public function agregar_menu_configuracion() {
        add_submenu_page(
            'edit.php?post_type=proyecto',
            __('Configuración del Portfolio', 'portfolio-personalizado'),
            __('Configuración', 'portfolio-personalizado'),
            'manage_options',
            'portfolio-config',
            array($this, 'pagina_configuracion')
        );
    }
    
    /**
     * Registrar configuraciones
     */
    public function registrar_configuraciones() {
        register_setting('portfolio_personalizado_opciones_grupo', 'portfolio_personalizado_opciones', array($this, 'sanitizar_opciones'));
        
        // Sección de colores
        add_settings_section(
            'portfolio_colores',
            __('Personalización de Colores', 'portfolio-personalizado'),
            array($this, 'seccion_colores_callback'),
            'portfolio-config'
        );
        
        // Campos de color
        $campos_color = array(
            'color_primario' => __('Color Primario', 'portfolio-personalizado'),
            'color_secundario' => __('Color Secundario', 'portfolio-personalizado'),
            'color_acento' => __('Color de Acento', 'portfolio-personalizado'),
            'color_fondo_card' => __('Color de Fondo de Card', 'portfolio-personalizado'),
            'color_texto_card' => __('Color de Texto de Card', 'portfolio-personalizado'),
            'color_fondo_imagen' => __('Color de Fondo de Imagen (Inicio)', 'portfolio-personalizado'),
            'color_fondo_imagen_secundario' => __('Color de Fondo de Imagen (Final)', 'portfolio-personalizado'),
            'color_boton' => __('Color de Botón', 'portfolio-personalizado'),
            'color_boton_hover' => __('Color de Botón (Hover)', 'portfolio-personalizado'),
            'color_texto_boton' => __('Color de Texto de Botón', 'portfolio-personalizado')
        );
        
        foreach ($campos_color as $campo => $label) {
            add_settings_field(
                $campo,
                $label,
                array($this, 'campo_color_callback'),
                'portfolio-config',
                'portfolio_colores',
                array('campo' => $campo)
            );
        }
        
        // Sección de estilos
        add_settings_section(
            'portfolio_estilos',
            __('Estilos Adicionales', 'portfolio-personalizado'),
            null,
            'portfolio-config'
        );
        
        add_settings_field(
            'border_radius_card',
            __('Border Radius de Cards (px)', 'portfolio-personalizado'),
            array($this, 'campo_numero_callback'),
            'portfolio-config',
            'portfolio_estilos',
            array('campo' => 'border_radius_card')
        );
        
        add_settings_field(
            'border_radius_boton',
            __('Border Radius de Botones (px)', 'portfolio-personalizado'),
            array($this, 'campo_numero_callback'),
            'portfolio-config',
            'portfolio_estilos',
            array('campo' => 'border_radius_boton')
        );
        
        // Sección de tags
        add_settings_section(
            'portfolio_tags',
            __('Configuración de Etiquetas', 'portfolio-personalizado'),
            array($this, 'seccion_tags_callback'),
            'portfolio-config'
        );
    }
    
    /**
     * Callbacks de secciones
     */
    public function seccion_colores_callback() {
        echo '<p>' . __('Personaliza los colores de tu portfolio. Los cambios se aplicarán automáticamente.', 'portfolio-personalizado') . '</p>';
        echo '<p><strong>' . __('Nota:', 'portfolio-personalizado') . '</strong> ' . __('Los colores de "Fondo de Imagen" crean un degradado de inicio a final.', 'portfolio-personalizado') . '</p>';
    }
    
    public function seccion_tags_callback() {
        $this->render_tags_manager();
    }
    
    /**
     * Callbacks de campos
     */
    public function campo_color_callback($args) {
        $campo = $args['campo'];
        $valor = isset($this->opciones[$campo]) ? $this->opciones[$campo] : $this->get_default_options()[$campo];
        echo '<input type="color" name="portfolio_personalizado_opciones[' . $campo . ']" value="' . esc_attr($valor) . '" />';
        echo ' <input type="text" value="' . esc_attr($valor) . '" readonly style="width: 100px;" />';
    }
    
    public function campo_numero_callback($args) {
        $campo = $args['campo'];
        $valor = isset($this->opciones[$campo]) ? $this->opciones[$campo] : $this->get_default_options()[$campo];
        echo '<input type="number" name="portfolio_personalizado_opciones[' . $campo . ']" value="' . esc_attr($valor) . '" min="0" max="100" />';
    }
    
    /**
     * Manager de tags personalizados
     */
    public function render_tags_manager() {
        $tags = isset($this->opciones['tags_personalizados']) ? $this->opciones['tags_personalizados'] : array();
        ?>
        <div id="portfolio-tags-manager">
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('Slug de Etiqueta', 'portfolio-personalizado'); ?></th>
                        <th><?php _e('Color de Fondo', 'portfolio-personalizado'); ?></th>
                        <th><?php _e('Color de Texto', 'portfolio-personalizado'); ?></th>
                        <th><?php _e('Acciones', 'portfolio-personalizado'); ?></th>
                    </tr>
                </thead>
                <tbody id="tags-list">
                    <?php foreach ($tags as $slug => $tag_data): ?>
                    <tr>
                        <td><input type="text" name="portfolio_personalizado_opciones[tags_personalizados][<?php echo esc_attr($slug); ?>][slug]" value="<?php echo esc_attr($slug); ?>" readonly /></td>
                        <td><input type="color" name="portfolio_personalizado_opciones[tags_personalizados][<?php echo esc_attr($slug); ?>][bg_color]" value="<?php echo esc_attr($tag_data['bg_color']); ?>" /></td>
                        <td><input type="color" name="portfolio_personalizado_opciones[tags_personalizados][<?php echo esc_attr($slug); ?>][text_color]" value="<?php echo esc_attr($tag_data['text_color']); ?>" /></td>
                        <td><button type="button" class="button remove-tag"><?php _e('Eliminar', 'portfolio-personalizado'); ?></button></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div style="margin-top: 20px;">
                <h4><?php _e('Agregar Nueva Etiqueta', 'portfolio-personalizado'); ?></h4>
                <input type="text" id="new-tag-slug" placeholder="<?php _e('Slug (ej: react, vue)', 'portfolio-personalizado'); ?>" />
                <input type="color" id="new-tag-bg" value="#ddd6fe" />
                <input type="color" id="new-tag-text" value="#6c5ce7" />
                <button type="button" id="add-tag" class="button button-primary"><?php _e('Agregar Etiqueta', 'portfolio-personalizado'); ?></button>
            </div>
        </div>
        <?php
    }
    
    /**
     * Sanitizar opciones
     */
    public function sanitizar_opciones($input) {
        $sanitized = array();
        
        $defaults = $this->get_default_options();
        
        foreach ($defaults as $key => $default) {
            if ($key === 'tags_personalizados') {
                $sanitized[$key] = isset($input[$key]) && is_array($input[$key]) ? $input[$key] : array();
            } elseif (strpos($key, 'color_') === 0) {
                $sanitized[$key] = isset($input[$key]) ? sanitize_hex_color($input[$key]) : $default;
            } else {
                $sanitized[$key] = isset($input[$key]) ? absint($input[$key]) : $default;
            }
        }
        
        return $sanitized;
    }
    
    /**
     * Página de configuración
     */
    public function pagina_configuracion() {
        ?>
        <div class="wrap">
            <h1><?php _e('Configuración del Portfolio Personalizado', 'portfolio-personalizado'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('portfolio_personalizado_opciones_grupo');
                do_settings_sections('portfolio-config');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
    
    /**
     * Scripts del admin
     */
    public function admin_enqueue_scripts($hook) {
        if ('proyecto_page_portfolio-config' !== $hook) {
            return;
        }
        
        wp_enqueue_script('portfolio-admin', PORTFOLIO_PERSONALIZADO_URL . 'assets/admin.js', array('jquery'), PORTFOLIO_PERSONALIZADO_VERSION, true);
        wp_enqueue_style('portfolio-admin-css', PORTFOLIO_PERSONALIZADO_URL . 'assets/admin.css', array(), PORTFOLIO_PERSONALIZADO_VERSION);
    }
    
    /**
     * Shortcode
     */
    public function mostrar_proyectos_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => -1,
            'columns' => 3,
            'category' => ''
        ), $atts);
        
        ob_start();
        
        $args = array(
            'post_type' => 'proyecto',
            'posts_per_page' => $atts['limit'],
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC'
        );
        
        if (!empty($atts['category'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'tecnologia',
                    'field' => 'slug',
                    'terms' => $atts['category']
                )
            );
        }
        
        $proyectos = get_posts($args);
        
        if ($proyectos): ?>
            <div class="portfolio-personalizado">
                <div class="row g-4">
                    <?php foreach($proyectos as $proyecto): 
                        $repo_url = get_post_meta($proyecto->ID, '_repo_url', true);
                        $demo_url = get_post_meta($proyecto->ID, '_demo_url', true);
                        $icono = get_post_meta($proyecto->ID, '_icono', true) ?: 'fas fa-code';
                        $tipo_enlace = get_post_meta($proyecto->ID, '_tipo_enlace', true) ?: 'github';
                        $tecnologias = get_the_terms($proyecto->ID, 'tecnologia');

                        $enlace_url = $repo_url;
                        $enlace_texto = __('Ver Repositorio', 'portfolio-personalizado');
                        $enlace_icono = 'fab fa-github';

                        if ($tipo_enlace === 'demo' && $demo_url) {
                            $enlace_url = $demo_url;
                            $enlace_texto = __('Ver Demo', 'portfolio-personalizado');
                            $enlace_icono = 'fas fa-external-link-alt';
                        } elseif ($tipo_enlace === 'download') {
                            $enlace_texto = __('Descargar', 'portfolio-personalizado');
                            $enlace_icono = 'fas fa-download';
                        }

                        $col_class = 'col-12 col-md-6 col-lg-' . (12 / $atts['columns']);
                    ?>
                        <div class="<?php echo esc_attr($col_class); ?>">
                            <div class="project-card">
                                <div class="project-image">
                                    <?php if (has_post_thumbnail($proyecto->ID)): ?>
                                        <?php echo get_the_post_thumbnail($proyecto->ID, 'medium', array('alt' => esc_attr($proyecto->post_title))); ?>
                                    <?php else: ?>
                                        <i class="<?php echo esc_attr($icono); ?>"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="project-content">
                                    <h5 class="project-title"><?php echo esc_html($proyecto->post_title); ?></h5>
                                    <?php if ($tecnologias): ?>
                                        <div class="project-tags">
                                            <?php foreach($tecnologias as $tech): ?>
                                                <span class="tag <?php echo esc_attr(strtolower($tech->slug)); ?>" data-tag="<?php echo esc_attr(strtolower($tech->slug)); ?>"><?php echo esc_html($tech->name); ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <p class="project-description"><?php echo wp_kses_post($proyecto->post_excerpt ?: wp_trim_words($proyecto->post_content, 20)); ?></p>
                                    <?php if ($enlace_url): ?>
                                        <a href="<?php echo esc_url($enlace_url); ?>" class="btn-repo" target="_blank" rel="noopener noreferrer">
                                            <i class="<?php echo esc_attr($enlace_icono); ?>"></i> <?php echo esc_html($enlace_texto); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <p><?php _e('No se encontraron proyectos.', 'portfolio-personalizado'); ?></p>
        <?php endif;
        
        return ob_get_clean();
    }
    
    /**
     * Registrar bloque de Gutenberg
     */
    public function registrar_bloque_gutenberg() {
        // Solo si Gutenberg está activo
        if (!function_exists('register_block_type')) {
            return;
        }

        wp_register_script(
            'portfolio-block',
            PORTFOLIO_PERSONALIZADO_URL . 'assets/block.js',
            array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n'),
            PORTFOLIO_PERSONALIZADO_VERSION,
            true
        );

        register_block_type('portfolio-personalizado/proyectos', array(
            'editor_script' => 'portfolio-block',
            'render_callback' => array($this, 'render_bloque_gutenberg'),
            'attributes' => array(
                'columns' => array(
                    'type' => 'number',
                    'default' => 3
                ),
                'limit' => array(
                    'type' => 'number',
                    'default' => -1
                ),
                'category' => array(
                    'type' => 'string',
                    'default' => ''
                )
            )
        ));
    }

    /**
     * Renderizar bloque de Gutenberg
     */
    public function render_bloque_gutenberg($attributes) {
        return $this->mostrar_proyectos_shortcode($attributes);
    }
    
    /**
     * Estilos y scripts frontend
     */
    public function enqueue_styles() {
        // Bootstrap
        wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css', array(), '5.3.6');
        
        // Font Awesome
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0');
        
        // Estilos del plugin
        $css_file = PORTFOLIO_PERSONALIZADO_PATH . 'assets/style.css';
        $css_url = PORTFOLIO_PERSONALIZADO_URL . 'assets/style.css';
        
        if (file_exists($css_file)) {
            wp_enqueue_style('portfolio-personalizado', $css_url, array('bootstrap', 'font-awesome'), PORTFOLIO_PERSONALIZADO_VERSION);
        } else {
            // Si no existe el archivo, usar estilos inline básicos
            wp_register_style('portfolio-personalizado-inline', false);
            wp_enqueue_style('portfolio-personalizado-inline');
            wp_add_inline_style('portfolio-personalizado-inline', $this->get_estilos_basicos());
        }
        
        // CSS dinámico con las opciones personalizadas
        $custom_css = $this->generar_css_personalizado();
        wp_add_inline_style('portfolio-personalizado', $custom_css);
    }
    
    /**
     * Estilos básicos inline (fallback)
     */
    private function get_estilos_basicos() {
        // Obtener colores para el gradiente
        $color_inicio = isset($this->opciones['color_fondo_imagen']) ? $this->opciones['color_fondo_imagen'] : '#7840c8';
        $color_fin = isset($this->opciones['color_fondo_imagen_secundario']) ? $this->opciones['color_fondo_imagen_secundario'] : '#9f5efb';
        
        return "
            .portfolio-personalizado { font-family: 'Inter', sans-serif; margin: 40px 0; }
            .portfolio-personalizado .row { display: flex; flex-wrap: wrap; margin: 0 -15px; }
            .portfolio-personalizado [class*='col-'] { padding: 0 15px; margin-bottom: 30px; }
            .portfolio-personalizado .project-card { background: white; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); overflow: hidden; height: 100%; transition: all 0.3s ease; }
            .portfolio-personalizado .project-card:hover { transform: translateY(-10px); box-shadow: 0 15px 50px rgba(0,0,0,0.15); }
            .portfolio-personalizado .project-image { height: 200px; background: linear-gradient(60deg, {$color_inicio} 0%, {$color_fin} 100%); display: flex; align-items: center; justify-content: center; }
            .portfolio-personalizado .project-image img { width: 100%; height: 100%; object-fit: cover; }
            .portfolio-personalizado .project-image i { font-size: 3rem; color: white; }
            .portfolio-personalizado .project-content { padding: 2rem; }
            .portfolio-personalizado .project-title { font-size: 1.4rem; font-weight: 700; margin-bottom: 0.5rem; }
            .portfolio-personalizado .project-description { color: #64748b; margin-bottom: 1.5rem; }
            .portfolio-personalizado .project-tags { display: flex; gap: 0.5rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
            .portfolio-personalizado .tag { background: #ddd6fe; color: #6c5ce7; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.8rem; }
            .portfolio-personalizado .btn-repo { background: #6c5ce7; color: white; padding: 0.8rem 1.5rem; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; }
            .portfolio-personalizado .btn-repo:hover { background: #a29bfe; color: white; text-decoration: none; }
            @media (min-width: 992px) {
                .portfolio-personalizado .col-lg-4 { flex: 0 0 33.3333%; max-width: 33.3333%; }
                .portfolio-personalizado .col-lg-3 { flex: 0 0 25%; max-width: 25%; }
                .portfolio-personalizado .col-lg-6 { flex: 0 0 50%; max-width: 50%; }
            }
            @media (min-width: 768px) and (max-width: 991px) {
                .portfolio-personalizado .col-md-6 { flex: 0 0 50%; max-width: 50%; }
            }
            @media (max-width: 767px) {
                .portfolio-personalizado .col-12 { flex: 0 0 100%; max-width: 100%; }
            }
        ";
    }
    
    /**
     * Generar CSS personalizado basado en las opciones
     */
    private function generar_css_personalizado() {
        $css = ":root {";
        $css .= "--primary-color: {$this->opciones['color_primario']};";
        $css .= "--secondary-color: {$this->opciones['color_secundario']};";
        $css .= "--accent-color: {$this->opciones['color_acento']};";
        $css .= "--card-bg-color: {$this->opciones['color_fondo_card']};";
        $css .= "--card-text-color: {$this->opciones['color_texto_card']};";
        $css .= "--image-bg-start: {$this->opciones['color_fondo_imagen']};";
        $css .= "--image-bg-end: {$this->opciones['color_fondo_imagen_secundario']};";
        $css .= "--button-color: {$this->opciones['color_boton']};";
        $css .= "--button-hover-color: {$this->opciones['color_boton_hover']};";
        $css .= "--button-text-color: {$this->opciones['color_texto_boton']};";
        $css .= "--card-border-radius: {$this->opciones['border_radius_card']}px;";
        $css .= "--button-border-radius: {$this->opciones['border_radius_boton']}px;";
        $css .= "}";
        
        // CSS para cards
        $css .= ".portfolio-personalizado .project-card { background: var(--card-bg-color); border-radius: var(--card-border-radius); }";
        $css .= ".portfolio-personalizado .project-title { color: var(--card-text-color); }";
        
        // CSS para imagen con gradiente personalizado
        $css .= ".portfolio-personalizado .project-image { background: linear-gradient(60deg, var(--image-bg-start) 0%, var(--image-bg-end) 100%); }";
        
        // CSS para botones
        $css .= ".portfolio-personalizado .btn-repo { background: var(--button-color); color: var(--button-text-color); border-radius: var(--button-border-radius); }";
        $css .= ".portfolio-personalizado .btn-repo:hover { background: var(--button-hover-color); color: var(--button-text-color); }";
        
        // CSS para tags personalizados
        if (!empty($this->opciones['tags_personalizados'])) {
            foreach ($this->opciones['tags_personalizados'] as $slug => $tag_data) {
                $css .= ".portfolio-personalizado .tag.{$slug} { background: {$tag_data['bg_color']}; color: {$tag_data['text_color']}; }";
            }
        }
        
        return $css;
    }
}

// Inicializar el plugin
new PortfolioPersonalizado();

// Hooks de activación / desactivación
register_activation_hook(__FILE__, function() { 
    flush_rewrite_rules(); 
});

register_deactivation_hook(__FILE__, function() { 
    flush_rewrite_rules(); 
});