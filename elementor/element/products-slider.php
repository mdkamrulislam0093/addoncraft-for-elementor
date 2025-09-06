<?php
/**
 * ACE Products Slider Widget for Elementor
 * 
 * @package AddonCraft Elementor
 * @since 1.1
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * ACE Product Slider Widget Class
 */
class ACE_Products_Slider extends \Elementor\Widget_Base {

    /**
     * Get widget name
     *
     * @return string Widget name
     */
    public function get_name() {
        return 'ace_products_slider';
    }

    /**
     * Get widget title
     *
     * @return string Widget title
     */
    public function get_title() {
        return esc_html__('Products Slider', 'addoncraft-for-elementor');
    }

    /**
     * Get widget icon
     *
     * @return string Widget icon
     */
    public function get_icon() {
        return 'eicon-post-slider';
    }
    
    /**
     * Get widget categories
     *
     * @return array Widget categories
     */
    public function get_categories() {
        return [ 'ACE_category_advanced' ];
    }

    /**
     * Get widget keywords
     *
     * @return array Widget keywords
     */
    public function get_keywords() {
        return ['woocommerce', 'product', 'slider', 'product slider'];
    }

    /**
     * Get style dependencies
     *
     * @return array Style dependencies
     */        
    public function get_style_depends() {
        return [ 'swiper' ];
    }

    /**
     * Get script dependencies
     *
     * @return array Script dependencies
     */
    public function get_script_depends() {
        return [ 'swiper' ];
    }

    /**
     * Check if WooCommerce is active
     *
     * @return bool
     */
    private function is_woocommerce_active() {
        return class_exists( 'WooCommerce' );
    }

    /**
     * Get all products for selection
     *
     * @return array Products array
     */       
    protected function get_all_products(){
        if ( ! $this->is_woocommerce_active() ) {
            return [];
        }

        $products_query = new WC_Product_Query( array(
            'limit' => -1,
            'return' => 'ids',
            'status' => 'publish',
        ) );

        $products = $products_query->get_products();

        if ( empty($products) ) {
            return [];
        }

        $all_products = [];

        foreach ($products as $product_id) {
            $product_title = get_the_title( $product_id );
            if ( $product_title ) {
                $all_products[ $product_id ] = $product_title;
            }
        }

        return $all_products;
    }

    /**
     * Add WooCommerce notice
     */
    private function add_woocommerce_notice() {
        $this->start_controls_section(
            'woocommerce_notice',
            [
                'label' => esc_html__( 'Notice', 'addoncraft-for-elementor' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'woocommerce_notice_text',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw'  => '<div style="color: #d9534f; padding: 15px; background: #f2dede; border: 1px solid #ebccd1; border-radius: 4px;">' .
                         esc_html__( 'WooCommerce plugin is required for this widget to work properly. Please install and activate WooCommerce.', 'addoncraft-for-elementor' ) .
                         '</div>',
            ]
        );

        $this->end_controls_section();
    }

   /**
     * Get product categories for dropdown
     */
    private function get_product_categories() {
        $categories = [];
        if (class_exists('WooCommerce')) {
            $terms = get_terms([
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
            ]);
            
            if (!is_wp_error($terms)) {
                foreach ($terms as $term) {
                    $categories[$term->term_id] = $term->name;
                }
            }
        }
        return $categories;
    }


    /**
     * Register widget controls
     */
    protected function _register_controls() {

        // Check if WooCommerce is active
        if ( ! $this->is_woocommerce_active() ) {
            $this->add_woocommerce_notice();
            return;
        }

        $this->register_product_display_controls();
        $this->register_query_controls();
        $this->register_settings_controls();
        $this->register_style_controls();
    }

    /**
     * Register Product Display
     */
    private function register_product_display_controls() {

        $this->start_controls_section(
            'product_display_section',
            [
                'label' => esc_html__('Display Settings', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ace_ss_show_feature_image',
            [
                'label'         => esc_html__( 'Show Feature Image', 'addoncraft-for-elementor' ),
                'type'          => \Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'addoncraft-for-elementor' ),
                'label_off'     => esc_html__( 'Hide', 'addoncraft-for-elementor' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
            ]
        );

        $this->add_control(
            'ace_ss_show_title',
            [
                'label' => __('Show Product Title', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'addoncraft-for-elementor'),
                'label_off' => __('No', 'addoncraft-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'ace_ss_product_description',
            [
                'label'         => esc_html__( 'Short Description', 'addoncraft-for-elementor' ),
                'type'          => \Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'addoncraft-for-elementor' ),
                'label_off'     => esc_html__( 'Hide', 'addoncraft-for-elementor' ),
                'return_value'  => 'yes',
                'default'       => 'no',
            ]
        );

        $this->add_control(
            'ace_ss_price',
            [
                'label' => __('Price', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'addoncraft-for-elementor'),
                'label_off' => __('No', 'addoncraft-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'ace_ss_rating',
            [
                'label' => __('Reviews', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'addoncraft-for-elementor'),
                'label_off' => __('No', 'addoncraft-for-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'ace_ss_category',
            [
                'label' => __('Categories', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'addoncraft-for-elementor'),
                'label_off' => __('No', 'addoncraft-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'ace_ss_tags',
            [
                'label' => __('Tags', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'addoncraft-for-elementor'),
                'label_off' => __('No', 'addoncraft-for-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'ace_ss_atc',
            [
                'label' => __('Show Add to Cart Button', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'addoncraft-for-elementor'),
                'label_off' => __('No', 'addoncraft-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'ace_ss_buy_btn',
            [
                'label' => __('Show Direct Buy Now Button', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'addoncraft-for-elementor'),
                'label_off' => __('No', 'addoncraft-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section(); 

    }


    /**
     * Register query controls
     */
    private function register_query_controls() {
        $this->start_controls_section(
            'ace_ss_query_section',
            [
                'label' => esc_html__('Query Settings', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ace_ss_content_source',
            [
                'label'     => esc_html__( 'Source', 'addoncraft-for-elementor' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'recent',
                'options'   => [
                    'recent'                => esc_html__( 'Latest Products', 'addoncraft-for-elementor' ),
                    'featured'              => esc_html__('Featured Products', 'addoncraft-for-elementor'),
                    'sale'                  => esc_html__('On Sale Products', 'addoncraft-for-elementor'),
                    'best_selling'          => esc_html__('Best Selling', 'addoncraft-for-elementor'),
                    'top_rated'             => esc_html__('Top Rated', 'addoncraft-for-elementor'),                 
                    'manually_selection'    => esc_html__( 'Manually Selection', 'addoncraft-for-elementor' ),
                ],
            ]
        );

        $this->add_control(
            'ace_ss_manually_product',
            [
                'label'         => esc_html__( 'Search & Select Products', 'addoncraft-for-elementor' ),
                'type'          => \Elementor\Controls_Manager::SELECT2,
                'label_block'   => true,
                'multiple'      => true,
                'options'       => $this->get_all_products(),
                'condition'     => [
                    'ace_ss_content_source' => [ 'manually_selection' ],
                ],
            ]
        );

        $this->add_control(
            'ace_ss_category_filter',
            [
                'label' => __('Product Categories', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_product_categories(),
                'condition' => [
                    'ace_ss_content_source!' => 'manually_selection',
                ],
            ]
        );        

        $this->add_control(
            'ace_ss_product_limit',
            [
                'label' => esc_html__( 'Limit', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 5,
            ]
        );

        $this->add_control(
            'ace_ss_product_order_by',
            [
                'label'     => esc_html__( 'Order By', 'addoncraft-for-elementor' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'options'   => [
                    'date'          => esc_html( 'Date' ),
                    'title'         => esc_html( 'Title' ),
                    'menu_order'    => esc_html( 'Menu Order' ),
                    'rand'          => esc_html( 'Random' ),
                ],
                'default'   => 'date',
            ]
        );

        $this->add_control(
            'ace_ss_product_order',
            [
                'label'     => esc_html__( 'Order', 'addoncraft-for-elementor' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'options'   => [
                    'ASC'     => esc_html( 'ASC' ),
                    'DESC'    => esc_html( 'DESC' ),
                ],
                'default'   => 'DESC',
            ]
        );        

        $this->end_controls_section();  
    }


    /**
     * Register settings controls
     */
    private function register_settings_controls() {

        $this->start_controls_section(
            'atc_ss_slider_settings_section',
            [
                'label' => esc_html__( 'Slider Settings', 'addoncraft-for-elementor' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $slides_to_show = range( 1, 10 );
        $slides_to_show = array_combine( $slides_to_show, $slides_to_show );

        $this->add_responsive_control(
            'ace_ss_slides_to_show',
            [
                'label' => esc_html__( 'Slides to Show', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Default', 'addoncraft-for-elementor' ),
                ] + $slides_to_show,
                'default' => 3,
                'frontend_available' => true,
                'render_type' => 'template',
            ]
        );

        $this->add_responsive_control(
            'ace_ss_slider_space_between',
            [
                'label' => esc_html__( 'Space Between', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
            ]
        );

        $this->add_control(
            'ace_ss_autoplay',
            [
                'label' => __('Autoplay', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'addoncraft-for-elementor'),
                'label_off' => __('No', 'addoncraft-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'ace_ss_pause_on_hover',
            [
                'label' => esc_html__( 'Pause on Hover', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'addoncraft-for-elementor' ),
                'label_off' => esc_html__( 'No', 'addoncraft-for-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'ace_ss_autoplay' => 'yes',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'ace_ss_pause_on_interaction',
            [
                'label' => esc_html__( 'Pause on Interaction', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'addoncraft-for-elementor' ),
                'label_off' => esc_html__( 'No', 'addoncraft-for-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'ace_ss_autoplay' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'ace_ss_autoplay_speed',
            [
                'label' => __('Autoplay Speed (ms)', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3000,
                'condition' => [
                    'ace_ss_autoplay' => 'yes',
                ],
            ]
        );


        $this->add_control(
            'ace_ss_infinite_loop',
            [
                'label' => __('Infinite Loop', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'addoncraft-for-elementor'),
                'label_off' => __('No', 'addoncraft-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'ace_ss_navigation',
            [
                'label' => esc_html__( 'Navigation', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'both',
                'options' => [
                    'both' => esc_html__( 'Arrows and Dots', 'addoncraft-for-elementor' ),
                    'arrows' => esc_html__( 'Arrows', 'addoncraft-for-elementor' ),
                    'dots' => esc_html__( 'Dots', 'addoncraft-for-elementor' ),
                    'none' => esc_html__( 'None', 'addoncraft-for-elementor' ),
                ],
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();
    }


    /**
     * Register style controls
     */
    private function register_style_controls() {
        

        $this->start_controls_section(
            'ACE_ss_initial_section',
            [
                'label' => esc_html__('Initial Style', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ace_ss_structure',
            [
                'label' => esc_html__( 'Layout', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::VISUAL_CHOICE,
                'label_block' => true,
                'options' => [
                    'default' => [
                        'title' => esc_attr__( 'Default', 'addoncraft-for-elementor' ),
                        'image' => ACE__PLUGIN_URL . 'assets/images/default-1.jpg',
                    ],
                    'coming-soon' => [
                        'title' => esc_attr__( 'Coming Soon', 'addoncraft-for-elementor' ),
                        'image' => ACE__PLUGIN_URL . 'assets/images/coming-soon.jpg',
                    ],
                    
                ],
                'default' => 'default',
                'columns' => 2,
                'condition' => [
                    'ace_ss_additional_style!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ace_ss_additional_style',
            [
                'label' => __('Custom Style', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'addoncraft-for-elementor'),
                'label_off' => __('No', 'addoncraft-for-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

         $this->end_controls_section();

         $this->register_layout_style_controls();

        $this->start_controls_section(
            'ACE_ss_section_general_style',
            [
                'label' => esc_html__('Product Card', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'ace_ss_additional_style',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                    ],
                ]
            ]
        );

        $this->add_control(
            'ace_ss_product_card_height',
            [
                'label' => esc_html__( 'Height', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'height-auto' => [
                        'title' => __('Auto', 'addoncraft-for-elementor'),
                        'icon' => 'eicon-justify-start-h',
                    ],
                    'height-full' => [
                        'title' => __('Fit', 'addoncraft-for-elementor'),
                        'icon' => 'eicon-justify-space-evenly-h',
                    ],
                    
                ],
                'default' => 'height-auto',
                'toggle' => true,
            ]
        );

        $this->add_control(
            'ace_ss_card_background',
            [
                'label' => __('Background Color', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_product-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'ace_ss_card_border',
                'label' => __('Border', 'addoncraft-for-elementor'),
                'selector' => '{{WRAPPER}} .ace_ss_product-card',
            ]
        );

        $this->add_control(
            'ace_ss_card_border_radius',
            [
                'label' => __('Border Radius', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_product-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'ace_ss_card_shadow',
                'label' => __('Box Shadow', 'addoncraft-for-elementor'),
                'selector' => '{{WRAPPER}} .ace_ss_product-card',
            ]
        );


        $this->add_responsive_control(
            'ace_ss_card_padding',
            [
                'label' => esc_html__( 'Padding', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_product-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Additional style sections for title, description, price, etc.
        // $this->register_content_style_controls();

        $this->register_title_style_controls();
        $this->register_description_style_controls();
        $this->register_feature_image_style_controls();
        $this->register_price_style_controls();
        $this->register_rating_style_controls();
        $this->register_categories_style_controls();
        $this->register_tags_style_controls();
        $this->register_button_style_controls();
        $this->register_buy_button_style_controls();
        $this->register_navigation_style_controls();
        $this->register_pagination_style_controls();
    }

    /**
     * Register Content style controls
     */
    public function register_layout_style_controls() {

        $this->start_controls_section(
            'ACE_product_content_style',
            [
                'label' => esc_html__('Inner Layout', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'ace_ss_additional_style',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                    ],
                ]
            ]
        );


        $this->add_responsive_control(
            'slider_container_padding',
            [
                'label' => esc_html__( 'Padding', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_swiper_product_sliders' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }


    /**
     * Register title style controls
     */
    private function register_title_style_controls() {

        $this->start_controls_section(
            'ACE_product_slider_title_style',
            [
                'label' => esc_html__('Title', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'ace_ss_additional_style',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'ace_ss_show_title',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                    ],
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} h3.ace_ss_product-title, {{WRAPPER}} h3.ace_ss_product-title a',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} h3.ace_ss_product-title' => 'color: {{VALUE}}',
                    '{{WRAPPER}} h3.ace_ss_product-title a' => 'color: {{VALUE}}',
                ],
                'global' => [
                    'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
                ],
            ]
        );

        $this->add_control(
            'ace_ps_title_gap',
            [
                'label' => esc_html__( 'Gap', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} h3.ace_ss_product-title' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Register description style controls
     */
    private function register_description_style_controls() {

        $this->start_controls_section(
            'ACE_product_slider_description_style',
            [
                'label' => esc_html__('Description', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'ace_ss_additional_style',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'ace_ss_product_description',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                    ],
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'ace_description_typography',
                'selector' => '{{WRAPPER}} .ace_ss_product-info .ace_ss_product-description',
            ]
        );

        $this->add_control(
            'ace_description_color',
            [
                'label' => esc_html__( 'Color', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_product-description' => 'color: {{VALUE}}',
                ],
                'global' => [
                    'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
                ],
            ]
        );

        $this->add_control(
            'ace_ps_description_gap',
            [
                'label' => esc_html__( 'Gap', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_product-description' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Register image style controls
     */
    private function register_feature_image_style_controls() {

        $this->start_controls_section(
            'ACE_product_image_style',
            [
                'label' => esc_html__('Image', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'ace_ss_additional_style',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'ace_ss_show_feature_image',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                    ],
                ]
            ]
        );

        $this->add_control(
            'ace_ss_feature_image_height',
            [
                'label' => esc_html__( 'Height', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .ace-product-image-wrapper' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ace_ss_feature_image_fit',
            [
                'label' => esc_html__( 'Image Fit', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    "fill" => "Fill",
                    "cover" => "Cover",
                    "contain" => "Contain",
                    "scale-down" => "Scale Down",
                ],
                'selectors' => [
                    '{{WRAPPER}} .ace-product-image-wrapper img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ace_ss_feature_image_background',
            [
                'label' => __('Background Color', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ace-product-image-wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'ace_ss_feature_image_border',
                'label' => __('Border', 'addoncraft-for-elementor'),
                'selector' => '{{WRAPPER}} .ace-product-image-wrapper',
            ]
        );

        $this->add_control(
            'ace_ss_feature_image_radius',
            [
                'label' => __('Border Radius', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ace-product-image-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ace_ss_feature_image_padding',
            [
                'label' => esc_html__( 'Padding', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_product-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

    }


    /**
     * Register price style controls
     */
    private function register_price_style_controls() {
        $this->start_controls_section(
            'ACE_product_slider_price_style',
            [
                'label' => esc_html__('Price', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'ace_ss_additional_style',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'ace_ss_price',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                    ],
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'ace_ss_price_typography',
                'selector' => '{{WRAPPER}} .ace_ss_product-price, {{WRAPPER}} .ace_ss_product-price *',
            ]
        );

        $this->add_control(
            'ace_ss_price_color',
            [
                'label' => esc_html__( 'Color', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_product-price' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ace_ss_product-price *' => 'color: {{VALUE}}',
                ],
                'global' => [
                    'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
                ],
            ]
        );

        $this->add_control(
            'ace_ps_price_gap',
            [
                'label' => esc_html__( 'Gap', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_product-price' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }


    /**
     * Register Rating style controls
     */
    private function register_rating_style_controls() {

        $this->start_controls_section(
            'ACE_product_slider_rating_style',
            [
                'label' => esc_html__('Rating', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'ace_ss_additional_style',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'ace_ss_rating',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                    ],
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'ace_ss_rating_typography',
                'selector' => '{{WRAPPER}} .ace_ss_product-rating .ace_ss_stars, {{WRAPPER}} .ace_ss_product-rating .ace_ss_rating-text',
            ]
        );

        $this->add_control(
            'ace_ss_rating_text_color',
            [
                'label' => esc_html__( 'Text Color', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_product-rating .ace_ss_rating-text' => 'color: {{VALUE}}',
                ],
                'global' => [
                    'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
                ],
            ]
        );

        $this->add_control(
            'ace_ss_rating_color_color',
            [
                'label' => esc_html__( 'Reviews Color', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_star_in' => 'color: {{VALUE}}',
                ],
                'global' => [
                    'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
                ],
            ]
        );

        $this->add_control(
            'ace_ps_rating_gap',
            [
                'label' => esc_html__( 'Gap', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_product-rating' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }


    /**
     * Register Categories style controls
     */
    private function register_categories_style_controls() {

        $this->start_controls_section(
            'ACE_product_slider_categories_style',
            [
                'label' => esc_html__('Category', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'ace_ss_additional_style',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'ace_ss_category',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                    ],
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'ace_ss_category_typography',
                'selector' => '{{WRAPPER}} .ace_ss_product-category, {{WRAPPER}} .ace_ss_product-category a',
            ]
        );

        $this->add_control(
            'ace_ss_category_color',
            [
                'label' => esc_html__( 'Color', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_product-category a' => 'color: {{VALUE}}',
                ],
                'global' => [
                    'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'atc_cat_background',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .ace_ss_product-category a',
            ]
        );

        $this->add_responsive_control(
            'ace_ss_cat_card_padding',
            [
                'label' => esc_html__( 'Padding', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_product-category a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ace_ss_category_gap',
            [
                'label' => esc_html__( 'Gap', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_product-category' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register Tags style controls
     */
    private function register_tags_style_controls() {

        $this->start_controls_section(
            'ACE_product_slider_tags_style',
            [
                'label' => esc_html__('Tags', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'ace_ss_additional_style',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'ace_ss_tags',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                    ],
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'ace_ss_tag_typography',
                'selector' => '{{WRAPPER}} .ace_ss_product-tag, {{WRAPPER}} .ace_ss_product-tag a',
            ]
        );

        $this->add_control(
            'ace_ss_tag_color',
            [
                'label' => esc_html__( 'Color', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_product-tag a' => 'color: {{VALUE}}',
                ],
                'global' => [
                    'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'atc_tag_background',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .ace_ss_product-tag a',
            ]
        );

        $this->add_responsive_control(
            'ace_ss_tags_card_padding',
            [
                'label' => esc_html__( 'Padding', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_product-tag a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ace_ss_tag_gap',
            [
                'label' => esc_html__( 'Gap', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_product-tag' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }


    /**
     * Register button style controls
     */
    private function register_button_style_controls() {
        $this->start_controls_section(
            'ACE_product_slider_atc_style',
            [
                'label' => esc_html__('Add to Cart', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'ace_ss_additional_style',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'ace_ss_atc',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                    ],
                ]               
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'atc_typography',
                'selector' => '{{WRAPPER}} .ace_ss_btn-primary',
            ]
        );

        $this->add_control(
            'atc_color',
            [
                'label' => esc_html__( 'Color', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_btn-primary' => 'color: {{VALUE}}',
                ],
                'global' => [
                    'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'atc_background',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .ace_ss_btn-primary',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'atc_border',
                'selector' => '{{WRAPPER}} .ace_ss_btn-primary',
            ]
        );

        $this->add_responsive_control(
            'atc_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 5,
                    'right' => 5,
                    'bottom' => 5,
                    'left' => 5,
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_btn-primary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'atc_bpadding',
            [
                'label' => esc_html__( 'Padding', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'default' => [
                    'top' => 10,
                    'right' => 30,
                    'bottom' => 10,
                    'left' => 30,
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_btn-primary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register Buy button style controls
     */
    private function register_buy_button_style_controls() {
        $this->start_controls_section(
            'ACE_product_slider_buy_atc_style',
            [
                'label' => esc_html__('Buy Button', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'ace_ss_additional_style',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'ace_ss_buy_btn',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                    ],
                ]               
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'atc_buy_typography',
                'selector' => '{{WRAPPER}} .ace-product-atc a',
            ]
        );

        $this->add_control(
            'atc_buy_color',
            [
                'label' => esc_html__( 'Color', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_btn-secondary' => 'color: {{VALUE}}',
                ],
                'global' => [
                    'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'atc_buy_background',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} a.ace_ss_btn-secondary',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'atc_buy_border',
                'selector' => '{{WRAPPER}} a.ace_ss_btn-secondary',
            ]
        );

        $this->add_responsive_control(
            'atc_buy_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 5,
                    'right' => 5,
                    'bottom' => 5,
                    'left' => 5,
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} a.ace_ss_btn-secondary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'atc_buy_padding',
            [
                'label' => esc_html__( 'Padding', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'default' => [
                    'top' => 10,
                    'right' => 30,
                    'bottom' => 10,
                    'left' => 30,
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_btn-secondary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }




    /**
     * Register Navigation style controls
     */
    private function register_navigation_style_controls() {
        $this->start_controls_section(
            'ACE_product_slider_navigation_style',
            [
                'label' => esc_html__('Navigation', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,  
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'ace_ss_additional_style',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                    ],
                ]
            ]
        );



        $this->start_controls_tabs(
            'ace_ss_navigation_orientation_tabs'
        );

            $this->start_controls_tab(
                'ace_ss_navigation_left_orient',
                [
                    'label' => esc_html__( 'Previous', 'addoncraft-for-elementor' ),
                ]
            );

            $this->add_control(
                'ace_ss_navigation_left_x_position',
                [
                    'label' => esc_html__( 'Horizontal', 'addoncraft-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'atc_ss_x_left' => [
                            'title' => __('Left', 'addoncraft-for-elementor'),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'atc_ss_x_right' => [
                            'title' => __('Right', 'addoncraft-for-elementor'),
                            'icon' => 'eicon-h-align-right',
                        ],
                        
                    ],
                    'default' => 'atc_ss_x_left',
                    'toggle' => true,
                ]
            );

            $this->add_control(
                'ace_ss_navigaiton_left_x_position_offset',
                [
                    'label' => esc_html__( 'Offset', 'addoncraft-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -500,
                            'max' => 500,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 30,
                    ]
                ]
            );

            $this->add_control(
                'navigation_position_horizontal_hr',
                [
                    'type' => \Elementor\Controls_Manager::DIVIDER,
                ]
            );

            $this->add_control(
                'ace_ss_navigation_left_y_position',
                [
                    'label' => esc_html__( 'Vertical', 'addoncraft-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'atc_ss_y_top' => [
                            'title' => __('Top', 'addoncraft-for-elementor'),
                            'icon' => 'eicon-v-align-top',
                        ],
                        'atc_ss_y_center' => [
                            'title' => __('Center', 'addoncraft-for-elementor'),
                            'icon' => 'eicon-v-align-middle',
                        ],
                        'atc_ss_y_bottom' => [
                            'title' => __('Bottom', 'addoncraft-for-elementor'),
                            'icon' => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default' => 'atc_ss_y_center',
                    'toggle' => true,
                ]
            );

            $this->add_control(
                'ace_ss_navigaiton_left_y_position_offset',
                [
                    'label' => esc_html__( 'Offset', 'addoncraft-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -500,
                            'max' => 500,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 0,
                    ],
                ]
            );



            $this->end_controls_tab();

            $this->start_controls_tab(
                'ace_ss_navigation_right_orient',
                [
                    'label' => esc_html__( 'Next', 'addoncraft-for-elementor' ),
                ]
            );


            $this->add_control(
                'ace_ss_navigation_right_x_position',
                [
                    'label' => esc_html__( 'Horizontal', 'addoncraft-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'atc_ss_x_left' => [
                            'title' => __('Left', 'addoncraft-for-elementor'),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'atc_ss_x_right' => [
                            'title' => __('Right', 'addoncraft-for-elementor'),
                            'icon' => 'eicon-h-align-right',
                        ],
                        
                    ],
                    'default' => 'atc_ss_x_right',
                    'toggle' => true,
                ]
            );

            $this->add_control(
                'ace_ss_navigaiton_right_x_position_offset',
                [
                    'label' => esc_html__( 'Offset', 'addoncraft-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -500,
                            'max' => 500,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => -30,
                    ]
                ]
            );

            $this->add_control(
                'navigation_position_left_horizontal_hr',
                [
                    'type' => \Elementor\Controls_Manager::DIVIDER,
                ]
            );

            $this->add_control(
                'ace_ss_navigation_right_y_position',
                [
                    'label' => esc_html__( 'Vertical', 'addoncraft-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'atc_ss_y_top' => [
                            'title' => __('Top', 'addoncraft-for-elementor'),
                            'icon' => 'eicon-v-align-top',
                        ],
                        'atc_ss_y_center' => [
                            'title' => __('Center', 'addoncraft-for-elementor'),
                            'icon' => 'eicon-v-align-middle',
                        ],
                        'atc_ss_y_bottom' => [
                            'title' => __('Bottom', 'addoncraft-for-elementor'),
                            'icon' => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default' => 'atc_ss_y_center',
                    'toggle' => true,
                ]
            );

            $this->add_control(
                'ace_ss_navigaiton_right_y_position_offset',
                [
                    'label' => esc_html__( 'Offset', 'addoncraft-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -500,
                            'max' => 500,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 0,
                    ],
                ]
            );

            $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'navigation_position_hr',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'ace_ss_navigation_background',
                'types' => [ 'classic' ],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .ace_ss_swiper_product_sliders-nav .swiper-button-prev, {{WRAPPER}} .ace_ss_swiper_product_sliders-nav .swiper-button-next',
            ]
        );

        $this->add_control(
            'ace_ss_navigation_color',
            [
                'label' => esc_html__( 'Color', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_swiper_product_sliders-nav .swiper-button-prev' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ace_ss_swiper_product_sliders-nav .swiper-button-next' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'ace_ss_navigaiton_size',
            [
                'label' => esc_html__( 'Size', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_swiper_product_sliders-nav .swiper-button-prev' => 'width: {{SIZE}}px; height: {{SIZE}}px; font-size: calc({{SIZE}}px / 2.5);',
                    '{{WRAPPER}} .ace_ss_swiper_product_sliders-nav .swiper-button-next' => 'width: {{SIZE}}px; height: {{SIZE}}px; font-size: calc({{SIZE}}px / 2.5);',
                ],
            ]
        );

        $this->add_responsive_control(
            'ace_ss_navigaiton_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 5,
                    'right' => 5,
                    'bottom' => 5,
                    'left' => 5,
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_swiper_product_sliders-nav .swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ace_ss_swiper_product_sliders-nav .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    /**
     * Register Pagination style controls
     */
    private function register_pagination_style_controls() {
        $this->start_controls_section(
            'ACE_product_slider_pagination_style',
            [
                'label' => esc_html__('Pagination', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,   
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'ace_ss_additional_style',
                            'operator' => '=',
                            'value' => 'yes',
                        ],
                    ],
                ]           
            ]
        );



        $this->start_controls_tabs(
            'ace_ss_pagination_background_tabs'
        );

        $this->start_controls_tab(
            'ace_ss_pagination_background_normal',
            [
                'label' => esc_html__( 'Normal', 'addoncraft-for-elementor' ),
            ]
        );

        $this->add_control(
            'ace_ss_pagination_normal_background',
            [
                'label' => esc_html__( 'Color', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_swiper_product_sliders-nav .swiper-pagination-bullet' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'ace_ss_pagination_background_active',
            [
                'label' => esc_html__( 'Active', 'addoncraft-for-elementor' ),
            ]
        );

        $this->add_control(
            'ace_ss_pagination_active_background',
            [
                'label' => esc_html__( 'Color', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_swiper_product_sliders-nav .swiper-pagination-bullet:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .ace_ss_swiper_product_sliders-nav .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->add_control(
            'ace_ss_pagination_size',
            [
                'label' => esc_html__( 'Size', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_swiper_product_sliders-nav .swiper-pagination-bullet' => 'height: {{SIZE}}px; width: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'ace_ss_pagination_gap',
            [
                'label' => esc_html__( 'Gap', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SLIDER,            
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_swiper_product_sliders-nav .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}px;',
                ],
            ]
        );


        $this->add_control(
            'ace_ss_pagination_space',
            [
                'label' => esc_html__( 'Offset Y', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => -200,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],                
                'selectors' => [
                    '{{WRAPPER}} .ace_ss_swiper_product_sliders-nav .swiper-pagination' => 'bottom: {{SIZE}}px;',
                ],
            ]
        );




        $this->end_controls_section();
    }

    /**
     * Get and validate product limit
     */
    private function get_product_limit($settings) {
        $limit = absint($settings['ace_ss_product_limit'] ?? 10);
        return min(max($limit, 1), 50);
    }

    /**
     * Optimized and sanitized product slider render method
     */

    protected function render() {
       // Get and sanitize settings
        $settings = $this->get_settings_for_display();

        if (empty($settings) || !is_array($settings)) {
            return;
        }

        $source = sanitize_text_field($settings['ace_ss_content_source'] ?? '');

        if (empty($source)) {
            return;
        }   
        

        // Sanitize display options
        $display_options = $this->sanitize_display_options($settings);
        $addition_options = $this->additional_options_slider($settings);
        $slider_settings = $this->get_slider_settings($settings);

        // Get products
        $products = $this->get_slider_products($settings, $source);

        if ( empty($products) ) {
            $this->render_no_products_message();
            return;
        }

        // Render slider
        $this->render_product_slider($products, $display_options, $slider_settings, $addition_options);

    }

    /**
     * Addtional options For Slider
     */
    private function additional_options_slider ($settings) {
        return [
            'card_height' => $settings['ace_ss_product_card_height'],
            'show_navigation' => $settings['ace_ss_navigation'],
            'additional_style' => $settings['ace_ss_additional_style'],
            'navigation_left_x_position' => $settings['ace_ss_navigation_left_x_position'],
            'navigation_left_y_position' => $settings['ace_ss_navigation_left_y_position'],
            'navigation_left_x_offset' => $settings['ace_ss_navigaiton_left_x_position_offset'],
            'navigation_left_y_offset' => $settings['ace_ss_navigaiton_left_y_position_offset'],
            'navigation_right_x_position' => $settings['ace_ss_navigation_right_x_position'],
            'navigation_right_y_position' => $settings['ace_ss_navigation_right_y_position'],
            'navigation_right_x_offset' => $settings['ace_ss_navigaiton_right_x_position_offset'],
            'navigation_right_y_offset' => $settings['ace_ss_navigaiton_right_y_position_offset'],

        ];
    }

    /**
     * Sanitize display options
     */
    private function sanitize_display_options($settings) {
        return [
            'show_image' => $settings['ace_ss_show_feature_image'],
            'show_title' => $settings['ace_ss_show_title'],
            'show_desc' => $settings['ace_ss_product_description'],
            'show_price' => $settings['ace_ss_price'],
            'show_rating' => $settings['ace_ss_rating'],
            'show_category' => $settings['ace_ss_category'],
            'show_tags' => $settings['ace_ss_tags'],
            'show_atc' => $settings['ace_ss_atc'],
            'show_buy_btn' => $settings['ace_ss_buy_btn'],
        ];
    }



    /**
     * Get sanitized slider settings for Swiper configuration
     *
     * @param array $settings Raw settings array from user input
     * @return array Sanitized settings array for Swiper initialization
     * @since 1.0.0
     */

    private function get_slider_settings($settings){

        // Validate input parameter
        if ( ! is_array( $settings ) ) {
            $settings = array();
        }

        // Slider Slider Responsive
        $default_slide = 1;
        $tablet_slide = 2;
        $desktop_slide = 3;

        if ( !empty($settings['ace_ss_slides_to_show']) ) {
            $desktop_slide = $settings['ace_ss_slides_to_show'];
            $default_slide = $settings['ace_ss_slides_to_show'];
        }

        if ( !empty($settings['ace_ss_slides_to_show_tablet']) ) {
            $tablet_slide = $settings['ace_ss_slides_to_show_tablet'];
            $default_slide = $settings['ace_ss_slides_to_show_tablet'];
        }

        if ( !empty($settings['ace_ss_slides_to_show_mobile']) ) {
            $default_slide = $settings['ace_ss_slides_to_show_mobile'];
        } 


        // Slider Slider Responsive
        $default_space = 10;
        $tablet_space = 20;
        $desktop_space = 30;

        if ( !empty($settings['ace_ss_slider_space_between']) ) {
            $desktop_space = $settings['ace_ss_slider_space_between']['size'];
            $default_space = $settings['ace_ss_slider_space_between']['size'];
        }

        if ( !empty($settings['ace_ss_slider_space_between_tablet']) ) {
            $tablet_space = $settings['ace_ss_slider_space_between_tablet']['size'];
            $default_space = $settings['ace_ss_slider_space_between_tablet']['size'];
        }

        if ( !empty($settings['ace_ss_slider_space_between_mobile']) ) {
            $default_space = $settings['ace_ss_slider_space_between_mobile']['size'];
        } 



        // Default settings with proper sanitization
        $settings_arr = array(
            'slidesPerView' => $this->sanitize_slides_per_view( $default_slide ?? 3 ),
            'spaceBetween'  => absint( $default_space ?? 30 ),
            'loop'          => $this->sanitize_boolean( $settings['ace_ss_infinite_loop'] ?? false ),
            'autoHeight'    => false,
            'keyboard'      => array(
                'enabled'        => true,
                'onlyInViewport' => true,
            ),
            'breakpoints'   => array(
                '768' => array(
                    'slidesPerView' => absint( $tablet_slide ),
                    'spaceBetween' => absint( $tablet_space ),
                ),
                '1024' => array(
                    'slidesPerView' => absint( $desktop_slide ),
                    'spaceBetween' => absint( $desktop_space ),
                ),
            )
        );


        // Handle autoplay settings
        if ( $this->sanitize_boolean( $settings['ace_ss_autoplay'] ?? false ) ) {
            $settings_arr['autoplay'] = array(
                'delay'                => $this->sanitize_autoplay_delay( $settings['ace_ss_autoplay_speed'] ?? 2500 ),
                'disableOnInteraction' => $this->sanitize_boolean( $settings['ace_ss_pause_on_interaction'] ?? false ),
                'pauseOnMouseEnter'    => $this->sanitize_boolean( $settings['ace_ss_pause_on_hover'] ?? false ),
            );
        }

        // Handle navigation settings
        $navigation_type = sanitize_text_field( $settings['ace_ss_navigation'] ?? 'none' );
        $this->configure_navigation( $settings_arr, $navigation_type );

        return apply_filters( 'ace_ss_slider_settings', $settings_arr, $settings, $this->get_id() );
    }


    /**
    * 
    * Configure navigation based on type
    */

    private function configure_navigation( &$settings_arr, $navigation_type ) {
        switch ( $navigation_type ) {
            case 'both':
                $settings_arr['navigation'] = array(
                    'nextEl' => '.swiper-button-next',
                    'prevEl' => '.swiper-button-prev',
                );
                $settings_arr['pagination'] = array(
                    'el'        => '.swiper-pagination',
                    'clickable' => true,
                );
                break;

            case 'arrows':
                $settings_arr['navigation'] = array(
                    'nextEl' => '.swiper-button-next',
                    'prevEl' => '.swiper-button-prev',
                );
                break;

            case 'dots':
                $settings_arr['pagination'] = array(
                    'el'        => '.swiper-pagination',
                    'clickable' => true,
                );
                break;

            case 'none':
            default:
                // Remove navigation and pagination if set to none or invalid value
                unset( $settings_arr['navigation'], $settings_arr['pagination'] );
                break;
        }
    }


    /**
    * Sanitize slides per view value
    */
    private function sanitize_slides_per_view( $value ) {
        if ( 'auto' === $value ) {
            return 'auto';
        }
        
        $int_value = absint( $value );
        return max( 1, min( 10, $int_value ) ); // Limit between 1-10 slides
    }


    /**
    * Sanitize autoplay delay value
    */
    private function sanitize_autoplay_delay( $value ) {
        $delay = absint( $value );
        return max( 500, min( 10000, $delay ) ); // Limit between 0.5-10 seconds
    }

    /**
    * Sanitize boolean values
    */
    private function sanitize_boolean( $value ) {
        return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
    }


    /**
     * Get products for slider
     */
    private function get_slider_products($settings, $source) {
        // Build query arguments
        $args = [
            'return' => 'ids',
            'status' => 'publish',
            'limit' => $this->get_product_limit($settings),
            'orderby' => sanitize_text_field($settings['ace_ss_product_order_by'] ?? 'date'),
            'order' => sanitize_text_field($settings['ace_ss_product_order'] ?? 'DESC')
        ];


        if ( $source == 'featured' ) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                ]
            ];
        } elseif ( $source == 'sale' ) {
            $args['meta_query'] = [
                'relation' => 'OR',
                [
                    'key' => '_sale_price',
                    'value' => 0,
                    'compare' => '>',
                    'type' => 'NUMERIC'
                ],
                [
                    'key' => '_min_variation_sale_price',
                    'value' => 0,
                    'compare' => '>',
                    'type' => 'NUMERIC'
                ]
            ];
        } elseif ( $source == 'best_selling' ) {
           $args['meta_key'] = 'total_sales';
           $args['orderby'] = 'meta_value_num';
        } elseif ( $source == 'top_rated' ) {
           $args['meta_key'] = '_wc_average_rating';
           $args['orderby'] = 'meta_value_num';
           $args['meta_query'] = array(
                array(
                    'key' => '_wc_review_count',
                    'value' => 0,
                    'compare' => '>'
                )
            );
        } elseif ($source === 'manually_selection' && !empty($settings['ace_ss_manually_product'])) {
            $manual_products = array_map('absint', (array) $settings['ace_ss_manually_product']);
            $manual_products = array_filter($manual_products); // Remove invalid IDs
            
            if (!empty($manual_products)) {
                $args['include'] = $manual_products;
            } else {
                return [];
            }
        }


       // Handle Category Products
        if ( !empty($settings['ace_ss_category_filter']) ) {
            $args['product_category_id'] = $settings['ace_ss_category_filter'];

        }

        $args = apply_filters('ace_ss_product_slider_query_args', $args, $settings, $this->get_id());
        
        try {
            $query = new WC_Product_Query($args);
            return $query->get_products();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Render no products message
     */
    private function render_no_products_message() {
        ?>
        <div class="ace-no-products">
            <p><?php esc_html_e('No products found.', 'addoncraft-for-elementor'); ?></p>
        </div>
        <?php
    }

    /**
     * Render the product slider
     */
    private function render_product_slider($products, $display_options, $slider_settings, $addition_options) {
        $slider_id = 'ace-sliders-' . $this->get_id();
        $data_attrs = $this->get_slider_data_attributes($addition_options);

        $slider_settings['slider_id'] = $slider_id;

        $layout_class = '';

        if ( $addition_options['additional_style'] == 'yes' ) {
            $layout_class = 'ace_ss_product_sliders';
        } else {
           $layout_class = 'default_ace_ss_product_sliders';
        }

        $card_height = $addition_options['card_height'] ?? '';

        ?>
        <div class="<?php echo esc_attr( $layout_class ); ?>">
            <div class="ace_ss_swiper_product_sliders-wrapper" data-nav="<?php echo esc_attr( $addition_options['show_navigation'] ); ?>" data-height="<?php echo esc_attr( $card_height ); ?>">
                <div id="<?php echo esc_attr($slider_id); ?>" class="swiper ace_ss_swiper_product_sliders" <?php echo wp_kses_post($data_attrs); ?> data-swiper_settings='<?php echo json_encode( $slider_settings, JSON_HEX_APOS | JSON_HEX_QUOT ); ?>'>
                    <div class="swiper-wrapper">
                        <?php foreach ($products as $product_id): ?>
                            <?php $this->render_product_slide($product_id, $display_options); ?>
                        <?php endforeach; ?>
                    </div>

                </div>
                <div class="ace_ss_swiper_product_sliders-nav">
                    <?php $this->render_slider_controls($addition_options); ?>                    
                </div>
            </div>
        </div>
        <?php $this->render_slider_script(); 
    }

    /**
     * Get slider data attributes
     */
    private function get_slider_data_attributes($display_options) {

        $attributes = [
            'data-navigation' => esc_attr( in_array($display_options['show_navigation'], ['arrows', 'both']) ? 'yes' : '0'),
            'data-pagination' => esc_attr( $display_options['show_navigation'] == 'dots' ? 'yes' : '0' ),
            'data-pagination' => esc_attr( $display_options['show_navigation'] == 'dots' ? 'yes' : '0' ),
            // 'data-image-direction' => esc_attr($display_options['box_image_position'])
        ];
        
        return implode(' ', array_map(function($key, $value) {
            return sprintf('%s="%s"', $key, $value );
        }, array_keys($attributes), $attributes));
    }

    /**
     * Render slider controls
     */
    private function render_slider_controls($addition_options) {
         $show_navigation = isset($addition_options['show_navigation']) ? $addition_options['show_navigation'] : '';


        if ( in_array($show_navigation, ['arrows', 'both']) ):

            $nav_left_pos = [ $addition_options['navigation_left_x_position'], $addition_options['navigation_left_y_position'] ];

            $nav_right_pos = [ $addition_options['navigation_right_x_position'], $addition_options['navigation_right_y_position'] ];

            $nav_left_pos_x_offset_size = $addition_options['navigation_left_x_offset']['size'] ?? 0;
            $nav_left_pos_x_offset_unit = $addition_options['navigation_left_x_offset']['unit'] ?? 'px';
            $nav_left_pos_y_offset_size = $addition_options['navigation_left_y_offset']['size'] ?? 0;
            $nav_left_pos_y_offset_unit = $addition_options['navigation_left_y_offset']['unit'] ?? 'px';
            
            $nav_right_pos_x_offset_size = $addition_options['navigation_right_x_offset']['size'] ?? 0;
            $nav_right_pos_x_offset_unit = $addition_options['navigation_right_x_offset']['unit'] ?? 'px';
            $nav_right_pos_y_offset_size = $addition_options['navigation_right_y_offset']['size'] ?? 0;
            $nav_right_pos_y_offset_unit = $addition_options['navigation_right_y_offset']['unit'] ?? 'px';

            $nav_left_classes = array_filter($nav_left_pos);
            $nav_right_classes = array_filter($nav_right_pos);
        ?> 
            <div class="swiper-button-prev <?php echo esc_attr( implode(' ', $nav_left_classes) ); ?>" style="transform: translate(<?php echo esc_attr( $nav_left_pos_x_offset_size . $nav_left_pos_x_offset_unit ); ?>, <?php echo esc_attr( $nav_left_pos_y_offset_size . $nav_left_pos_y_offset_unit ); ?>);"></div>

            <div class="swiper-button-next <?php echo esc_attr( implode(' ', $nav_right_classes) ); ?>" style="transform: translate(<?php echo esc_attr( $nav_right_pos_x_offset_size . $nav_right_pos_x_offset_unit ); ?>, <?php echo esc_attr( $nav_right_pos_y_offset_size . $nav_right_pos_y_offset_unit ); ?>);"></div>

        <?php endif;
        
        if ( in_array($addition_options['show_navigation'], ['dots', 'both']) ): ?>
            <div class="swiper-pagination"></div>
        <?php endif;
    }    


    private function render_slider_script() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                if (typeof Swiper === 'undefined') {
                    console.log('Swiper library not loaded');
                    return;
                }

                let current_slider = $("#ace-sliders-<?php echo esc_js($this->get_id()); ?>");

                if ( current_slider.length ) {
                    try {
                      const opts = JSON.parse(current_slider.attr('data-swiper_settings') || '{}');
                      console.log(opts);
                      new Swiper('#ace-sliders-<?php echo esc_js($this->get_id()); ?>', opts);
                    } catch (e) {
                      console.error('Invalid data-swiper JSON', e);
                    }
                }

            });


        </script>

        <?php 
    }


    /**
     * Render individual product slide
     */
    private function render_product_slide($product_id, $display_options) {
        $product = wc_get_product($product_id);
        
        if (!$product || !$product->is_visible()) {
            return;
        }
        
        $product_data = $this->prepare_product_data($product);
        
        ?>
        <div class="swiper-slide">
            <div class="ace_ss_product-card">
                <?php 
                    if ( $display_options['show_image'] ): 
                        $this->render_product_image($product_data); 
                    endif 
                ?>
                
                <div class="ace_ss_product-info">
                    <?php do_action( 'before_ace_ss_products_slider_content' ); ?>
                    <?php $this->render_product_content($product_data, $display_options); ?>
                    <?php do_action( 'after_ace_ss_products_slider_content' ); ?>
                </div>
                
            </div>
        </div>
        <?php
    }

    /**
     * Prepare product data
     */
    private function prepare_product_data($product) {
        $description = $product->get_description();
        $short_description = $product->get_short_description();
        
        // Use short description first, fallback to trimmed full description
        $excerpt = '';
        if (!empty($short_description)) {
            $excerpt = wp_trim_words($short_description, 20);
        } elseif (!empty($description)) {
            $excerpt = wp_trim_words($description, 20);
        }

        if ( $product->get_reviews_allowed() ) {
            $average_rating = $product->get_average_rating();
            $count_rating = $product->get_review_count();
        } else {
            $average_rating = '';
            $count_rating = '';
        }
        return [
            'id' => $product->get_id(),
            'sku' => $product->get_sku(),
            'name' => $product->get_name(),
            'permalink' => get_permalink($product->get_id()),
            'type' => $product->get_type(),
            'image' => $this->get_product_image($product),
            'excerpt' => $excerpt,
            'price_html' => $product->get_price_html(),
            'add_to_cart_url' => $product->add_to_cart_url(),
            'add_to_cart_text' => $product->add_to_cart_text(),
            'tags' => $product->get_tag_ids(),
            'allowed_rating' => $product->get_reviews_allowed(),
            'average_rating' => $average_rating,
            'count_rating' => $count_rating
        ];
    }

    /**
     * Render product image
     */
    private function render_product_image($product_data) {
        ?>
        <div class="ace_ss_product-image">                
            <a href="<?php echo esc_url($product_data['permalink']); ?>" title="<?php echo esc_attr($product_data['name']); ?>">
                <div class="ace-product-image-wrapper">
                        <?php echo wp_kses_post($product_data['image']); ?>
                </div>
            </a>
        </div>
        <?php
    }


    /**
     * Render product content
     */
    private function render_product_content($product_data, $display_options) {
        ?>

        <div class="ace_ss_product_adinfo">
        <?php if ($display_options['show_category']): ?>
            <div class="ace_ss_product-category">
                <?php echo wp_kses_post( wc_get_product_category_list( $product_data['id'], '' ) ); ?>
            </div>
            <?php do_action( 'after_ace_ss_products_slider_category' ); ?>
        <?php endif; ?>

        <?php if ($display_options['show_title']): ?>
            <?php do_action( 'before_ace_ss_products_slider_title' ); ?>
            <h3 class="ace_ss_product-title">
                <a href="<?php echo esc_url($product_data['permalink']); ?>">
                    <?php echo esc_html($product_data['name']); ?>
                </a>
            </h3>
            <?php do_action( 'after_ace_ss_products_slider_title' ); ?>
        <?php endif; ?>

        <?php if ($display_options['show_desc'] && !empty($product_data['excerpt'])): ?>
            <div class="ace_ss_product-description">
                <?php echo esc_html($product_data['excerpt']); ?>
            </div>
        <?php endif; ?>

        <?php if ( !empty($display_options['show_rating']) && $product_data['count_rating'] > 0 ): 
            $rating_avarge = (( $product_data['average_rating'] / 5 ) * 100) > 0 ? ( $product_data['average_rating'] / 5 ) * 100 : 0;
            ?>
            <div class="ace_ss_product-rating">
                <div class="ace_ss_stars">
                    <div class="ace_ss_star_in" style="width: <?php echo esc_attr( $rating_avarge ); ?>%"></div>
                </div>
                <span class="ace_ss_rating-text">(<?php echo esc_html( $product_data['average_rating'] ); ?>) <?php echo esc_html( $product_data['count_rating'] ); ?> reviews</span>
            </div>
        <?php endif; ?>

        <?php if ($display_options['show_tags']): ?>        
            <div class="ace_ss_product-tags">
                <?php echo get_the_term_list( $product_data['id'], 'product_tag', '', '', '' ); ?>
            </div>
        <?php endif; ?>

        <?php if ($display_options['show_price']): ?>
            <div class="ace_ss_product-price">
                <?php echo wp_kses_post($product_data['price_html']); ?>
            </div>
        <?php endif; ?>

        </div>
        <div class="ace_ss_product_actions_wrap">
            <?php if ( $display_options['show_atc'] || $display_options['show_buy_btn'] ): ?>
                <div class="ace_ss_product-actions">
                    <?php if ( $display_options['show_atc'] ): ?>
                        <?php if ( $product_data['type'] == 'simple' ): ?>
                            <a href="<?php echo esc_url($product_data['add_to_cart_url']); ?>" aria-describedby="woocommerce_loop_add_to_cart_link_describedby_<?php echo esc_attr($product_data['id']); ?>" data-quantity="1" class="ace_ss_btn ace_ss_btn-primary button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr($product_data['id']); ?>" data-product_sku="<?php echo esc_attr($product_data['sku']); ?>" aria-label="Add to cart: '<?php echo esc_attr($product_data['name']); ?>'" rel="nofollow" data-success_message="'<?php echo esc_attr($product_data['name']); ?>' has been added to your cart" role="button"><?php echo esc_html($product_data['add_to_cart_text']); ?></a> 
                        <?php else : ?>
                            <a href="<?php echo esc_url($product_data['add_to_cart_url']); ?>"  
                               class="ace_ss_btn ace_ss_btn-primary ajax_add_to_cart "
                               data-product-id="<?php echo esc_attr($product_data['id']); ?>" data-quantity="1">
                                <?php echo esc_html($product_data['add_to_cart_text']); ?>
                            </a>
                        <?php endif ?>
                    <?php endif; ?>

                    <?php if ( $display_options['show_buy_btn'] && $product_data['type'] == 'simple' ): ?>
                        <a href="<?php echo esc_url( home_url( sprintf('/checkout/?add-to-cart=%d', $product_data['id']) ) ); ?>" 
                           class="ace_ss_btn ace_ss_btn-secondary"
                           data-product-id="<?php echo esc_attr($product_data['id']); ?>">Buy Now</a>
                    <?php endif; ?>
                </div>
            <?php endif;  ?>
        </div>
        <?php 

    }

    /**
     * Get product image safely
     */
    private function get_product_image($product) {
        $image_id = $product->get_image_id();
        
        if (!$image_id) {
            return wc_placeholder_img('full');
        }
        
        $image = wp_get_attachment_image($image_id, 'full', false, [
            'class' => 'ace-product-image',
            'loading' => 'lazy'
        ]);
        
        return $image ?: wc_placeholder_img('full');
    }

    protected function content_template() {}    
}