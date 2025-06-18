<?php
/**
 * ACE Product Slider Widget for Elementor
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
class ACE_Product_Slider extends \Elementor\Widget_Base {

    /**
     * Get widget name
     *
     * @return string Widget name
     */
    public function get_name() {
        return 'ace_product_slider';
    }

    /**
     * Get widget title
     *
     * @return string Widget title
     */
    public function get_title() {
        return esc_html__('Product Slider', 'addoncraft-for-elementor');
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
     * Register widget controls
     */
    protected function _register_controls() {

        // Check if WooCommerce is active
        if ( ! $this->is_woocommerce_active() ) {
            $this->add_woocommerce_notice();
            return;
        }

        $this->register_content_controls();
        $this->register_query_controls();
        $this->register_settings_controls();
        $this->register_style_controls();
    }

    /**
     * Register content controls
     */
    private function register_content_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_product_title',
            [
                'label'         => esc_html__( 'Show Title', 'addoncraft-for-elementor' ),
                'type'          => \Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'addoncraft-for-elementor' ),
                'label_off'     => esc_html__( 'Hide', 'addoncraft-for-elementor' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
            ]
        );

        $this->add_control(
            'show_feature_image',
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
            'show_product_description',
            [
                'label'         => esc_html__( 'Show Short Description', 'addoncraft-for-elementor' ),
                'type'          => \Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'addoncraft-for-elementor' ),
                'label_off'     => esc_html__( 'Hide', 'addoncraft-for-elementor' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
            ]
        );

        $this->add_control(
            'show_product_price',
            [
                'label'         => esc_html__( 'Show Price', 'addoncraft-for-elementor' ),
                'type'          => \Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'addoncraft-for-elementor' ),
                'label_off'     => esc_html__( 'Hide', 'addoncraft-for-elementor' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
            ]
        );

        $this->add_control(
            'show_add_to_cart',
            [
                'label'         => esc_html__( 'Show Add To Cart', 'addoncraft-for-elementor' ),
                'type'          => \Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'addoncraft-for-elementor' ),
                'label_off'     => esc_html__( 'Hide', 'addoncraft-for-elementor' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register query controls
     */
    private function register_query_controls() {
        $this->start_controls_section(
            'query_section',
            [
                'label' => esc_html__('Query', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'content_source',
            [
                'label'     => esc_html__( 'Source', 'addoncraft-for-elementor' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'latest_products',
                'options'   => [
                    'latest_products'       => esc_html__( 'Latest Products', 'addoncraft-for-elementor' ),
                    'manually_selection'    => esc_html__( 'Manually Selection', 'addoncraft-for-elementor' ),
                ],
            ]
        );

        $this->add_control(
            'manually_product',
            [
                'label'         => esc_html__( 'Search & Select Products', 'addoncraft-for-elementor' ),
                'type'          => \Elementor\Controls_Manager::SELECT2,
                'label_block'   => true,
                'multiple'      => true,
                'options'       => $this->get_all_products(),
                'condition'     => [
                    'content_source' => [ 'manually_selection' ],
                ],
            ]
        );

        $this->add_control(
            'ace_product_limit',
            [
                'label' => esc_html__( 'Product Limit', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 1,
            ]
        );

        $this->add_control(
            'product_order_by',
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
            'product_order',
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
            'slider_settings_section',
            [
                'label' => esc_html__( 'Slider Settings', 'addoncraft-for-elementor' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ace_show_navigation',
            [
                'label' => esc_html__( 'Show Navigation', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'addoncraft-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'addoncraft-for-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'ace_ps_show_pagination',
            [
                'label' => esc_html__( 'Show Pagination', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'addoncraft-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'addoncraft-for-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );


        $this->end_controls_section();
    }

    /**
     * Register style controls
     */
    private function register_style_controls() {

        $this->start_controls_section(
            'ACE_section_general_style',
            [
                'label' => esc_html__('General', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'box_content_aligement',
            [
                'label' => esc_html__( 'Content Alignment', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => __('Start', 'addoncraft-for-elementor'),
                        'icon' => 'eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => __('Center', 'addoncraft-for-elementor'),
                        'icon' => 'eicon-align-center-v',
                    ],
                    'end' => [
                        'title' => __('End', 'addoncraft-for-elementor'),
                        'icon' => 'eicon-align-end-v',
                    ],
                    
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .content-wrap' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'box_content_justify',
            [
                'label' => esc_html__( 'Justify Content', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Start', 'addoncraft-for-elementor'),
                        'icon' => 'eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => __('Center', 'addoncraft-for-elementor'),
                        'icon' => 'eicon-justify-center-h',
                    ],
                    'right' => [
                        'title' => __('End', 'addoncraft-for-elementor'),
                        'icon' => 'eicon-justify-end-h',
                    ],
                    
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .content-wrap' => 'text-align: {{VALUE}};',
                ],
            ]
        );



        $this->add_control(
            'box_gap',
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
                    '{{WRAPPER}} .ace-container-inner' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_box_padding',
            [
                'label' => esc_html__( 'Content Box Padding', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .content-wrap .content_wrapper_in' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Additional style sections for title, description, price, etc.
        $this->register_title_style_controls();
        $this->register_image_style_controls();
        $this->register_description_style_controls();
        $this->register_price_style_controls();
        $this->register_button_style_controls();
        $this->register_navigation_style_controls();
        $this->register_pagination_style_controls();
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
                'condition' => [
                    'show_product_title' => 'yes'
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .content-wrap h3.ace-product-title, {{WRAPPER}} .content-wrap h2.ace-product-title a',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .content-wrap h3.ace-product-title' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .content-wrap h3.ace-product-title a' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} .content-wrap h3.ace-product-title' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Register image style controls
     */
    private function register_image_style_controls() {

        $this->start_controls_section(
            'ACE_product_image_style',
            [
                'label' => esc_html__('Image', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_feature_image' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'box_image_position',
            [
                'label' => esc_html__( 'Image Position', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'addoncraft-for-elementor'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'top' => [
                        'title' => __('Top', 'addoncraft-for-elementor'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'right' => [
                        'title' => __('Right', 'addoncraft-for-elementor'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    
                ],
                'default' => 'right',
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'toggle' => true,
            ]
        );

        $this->add_control(
            'image_width',
            [
                'label' => esc_html__( 'Width', 'addoncraft-for-elementor' ),
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
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'tablet_default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'mobile_default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ace-product-image-wrapper img' => 'width: {{SIZE}}{{UNIT}};',
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
                'label' => esc_html__('Content', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_product_description' => 'yes'
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .content-wrap .ace-product-excerpt',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Color', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .content-wrap .ace-product-excerpt' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} .content-wrap .ace-product-excerpt' => 'padding-bottom: {{SIZE}}{{UNIT}};',
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
                'condition' => [
                    'show_product_price' => 'yes'
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} .ace-product-price, {{WRAPPER}} .ace-product-price *',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => esc_html__( 'Color', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ace-product-price' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ace-product-price *' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} .content-wrap .ace-product-price' => 'padding-bottom: {{SIZE}}{{UNIT}};',
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
                'condition' => [
                    'show_add_to_cart' => 'yes'
                ]                
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'atc_typography',
                'selector' => '{{WRAPPER}} .ace-product-atc a',
            ]
        );

        $this->add_control(
            'atc_color',
            [
                'label' => esc_html__( 'Color', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ace-product-atc a' => 'color: {{VALUE}}',
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
                'selector' => '{{WRAPPER}} .ace-product-atc a',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'atc_border',
                'selector' => '{{WRAPPER}} .ace-product-atc a',
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
                    '{{WRAPPER}} .ace-product-atc a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'atc_padding',
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
                    '{{WRAPPER}} .ace-product-atc a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'navigation_background',
                'types' => [ 'classic' ],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .ace-product-slider .swiper-button-prev, {{WRAPPER}} .ace-product-slider .swiper-button-next',
            ]
        );

        $this->add_control(
            'navigation_color',
            [
                'label' => esc_html__( 'Color', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .ace-product-slider .swiper-button-prev:after' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ace-product-slider .swiper-button-next:after' => 'color: {{VALUE}}',
                ],
            ]
        );


        $this->end_controls_section();
    }

    /**
     * Register Navigation style controls
     */
    private function register_pagination_style_controls() {
        $this->start_controls_section(
            'ACE_product_slider_pagination_style',
            [
                'label' => esc_html__('Pagination', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,              
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'pagination_background',
                'types' => [ 'classic' ],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .ace-product-slider .swiper-pagination',
            ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label' => esc_html__( 'Color', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .ace-product-slider .swiper-pagination .swiper-pagination-bullet' => 'background: {{VALUE}}',
                ],
            ]
        );


        $this->end_controls_section();
    }

    /**
     * Get and validate product limit
     */
    private function get_product_limit($settings) {
        $limit = absint($settings['ace_product_limit'] ?? 10);
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

        $source = sanitize_text_field($settings['content_source'] ?? '');
        
        if (empty($source)) {
            return;
        }   
        
        // Sanitize display options
        $display_options = $this->sanitize_display_options($settings);

        // Get products
        $products = $this->get_slider_products($settings, $source);

        if ( empty($products) ) {
            $this->render_no_products_message();
            return;
        }

        // Render slider
        $this->render_product_slider($products, $display_options);

    }


    /**
     * Sanitize display options
     */
    private function sanitize_display_options($settings) {
        return [
            'show_image' => !empty($settings['show_feature_image']),
            'show_title' => !empty($settings['show_product_title']),
            'show_desc' => !empty($settings['show_product_description']),
            'show_price' => !empty($settings['show_product_price']),
            'show_atc' => !empty($settings['show_add_to_cart']),
            'box_image_position' => sanitize_text_field($settings['box_image_position'] ?? 'right'),
            'show_navigation' => !empty($settings['ace_show_navigation']),
            'show_pagination' => !empty($settings['ace_ps_show_pagination'])
        ];
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
            'orderby' => sanitize_text_field($settings['product_order_by'] ?? 'date'),
            'order' => sanitize_text_field($settings['product_order'] ?? 'DESC')
        ];

        if ( $source == 'manually_selection' ) {
            $args['include'] = $settings['manually_product'];
        }

       // Handle manual selection
        if ($source === 'manually_selection' && !empty($settings['manually_product'])) {
            $manual_products = array_map('absint', (array) $settings['manually_product']);
            $manual_products = array_filter($manual_products); // Remove invalid IDs
            
            if (!empty($manual_products)) {
                $args['include'] = $manual_products;
            } else {
                return [];
            }
        }

        $args = apply_filters('ace_product_slider_query_args', $args, $settings);
        
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
    private function render_product_slider($products, $display_options) {
        $slider_id = 'ace-slider-' . uniqid();
        $data_attrs = $this->get_slider_data_attributes($display_options);

        ?>
        <div id="<?php echo esc_attr($slider_id); ?>" class="ace-product-slider swiper" <?php echo wp_kses_post($data_attrs); ?>>
            <div class="swiper-wrapper">
                <?php foreach ($products as $product_id): ?>
                    <?php $this->render_product_slide($product_id, $display_options); ?>
                <?php endforeach; ?>
            </div>

            <?php $this->render_slider_controls($display_options); ?>
        </div>

        <?php $this->render_slider_script(); 
    }

    /**
     * Get slider data attributes
     */
    private function get_slider_data_attributes($display_options) {
        $attributes = [
            'data-navigation' => esc_attr($display_options['show_navigation']) ? 'yes' : '0',
            'data-pagination' => esc_attr($display_options['show_pagination']) ? 'yes' : '0',
            'data-image-direction' => esc_attr($display_options['box_image_position'])
        ];
        
        return implode(' ', array_map(function($key, $value) {
            return sprintf('%s="%s"', $key, $value );
        }, array_keys($attributes), $attributes));
    }

    /**
     * Render slider controls
     */
    private function render_slider_controls($display_options) {
        if ($display_options['show_navigation']): ?>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        <?php endif;
        
        if ($display_options['show_pagination']): ?>
            <div class="swiper-pagination"></div>
        <?php endif;
    }    


    private function render_slider_script() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                if (typeof Swiper === 'undefined') {
                    console.error('Swiper library not loaded');
                    return;
                }

                var swiper = new Swiper(".ace-product-slider.swiper", {
                  slidesPerView: 1,
                  spaceBetween: 30,
                  navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                  },
                  pagination: {
                    el: ".swiper-pagination",
                    dynamicBullets: true,
                    clickable: true,
                  },
                });
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
            <div class="ace-product-slider-wrapper ace-container-inner">
                
                <?php if ($display_options['box_image_position'] === 'left' && $display_options['show_image']): ?>
                    <div class="ace-flex-column feature-wrap">
                        <?php $this->render_product_image($product_data); ?>
                    </div>
                <?php endif; ?>
                
                <div class="ace-flex-column content-wrap">
                    <?php if ($display_options['box_image_position'] === 'top' && $display_options['show_image']): ?>
                        <?php $this->render_product_image($product_data); ?>
                    <?php endif; ?>
                    
                    <div class="ace-column-wrapper content_wrapper_in">
                        <?php $this->render_product_content($product_data, $display_options); ?>
                    </div>
                </div>
                
                <?php if ($display_options['box_image_position'] === 'right' && $display_options['show_image']): ?>
                    <div class="ace-flex-column feature-wrap">
                        <?php $this->render_product_image($product_data); ?>
                    </div>
                <?php endif; ?>
                
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
        
        return [
            'id' => $product->get_id(),
            'name' => $product->get_name(),
            'permalink' => get_permalink($product->get_id()),
            'image' => $this->get_product_image($product),
            'excerpt' => $excerpt,
            'price_html' => $product->get_price_html(),
            'add_to_cart_url' => $product->add_to_cart_url(),
            'add_to_cart_text' => $product->add_to_cart_text()
        ];
    }

    /**
     * Render product image
     */
    private function render_product_image($product_data) {
        ?>
        <div class="ace-product-image-wrapper">
            <a href="<?php echo esc_url($product_data['permalink']); ?>" 
               title="<?php echo esc_attr($product_data['name']); ?>">
                <?php echo wp_kses_post($product_data['image']); ?>
            </a>
        </div>
        <?php
    }


    /**
     * Render product content
     */
    private function render_product_content($product_data, $display_options) {
        ?>
        <?php if ($display_options['show_title']): ?>
            <h3 class="ace-product-title">
                <a href="<?php echo esc_url($product_data['permalink']); ?>">
                    <?php echo esc_html($product_data['name']); ?>
                </a>
            </h3>
        <?php endif; ?>
        
        <?php if ($display_options['show_desc'] && !empty($product_data['excerpt'])): ?>
            <div class="ace-product-excerpt">
                <?php echo esc_html($product_data['excerpt']); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($display_options['show_price']): ?>
            <div class="ace-product-price">
                <?php echo wp_kses_post($product_data['price_html']); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($display_options['show_atc']): ?>
            <div class="ace-product-atc">
                <a href="<?php echo esc_url($product_data['add_to_cart_url']); ?>" 
                   class="button ace-atc-button"
                   data-product-id="<?php echo esc_attr($product_data['id']); ?>">
                    <?php echo esc_html($product_data['add_to_cart_text']); ?>
                </a>
            </div>
        <?php endif; 
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