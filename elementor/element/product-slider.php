<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class ACE_Product_Slider extends \Elementor\Widget_Base {
    public function get_name() {
        return 'ACE_Product_Slider';
    }

    public function get_title() {
        return esc_html__('Product Slider', 'addoncraft-elementor');
    }

    public function get_icon() {
        return 'eicon-frame-expand';
    }
    
    public function get_categories() {
        return [ 'ACE_category_advanced' ];
    }

    public function get_keywords() {
        return ['woocommerce', 'product', 'slider', 'product slider'];
    }

    // public function get_custom_help_url(): string {
    //     return 'https://example.com/widget-name';
    // }

    // protected function get_upsale_data(): array {
    //     return [
    //         'condition' => ! \Elementor\Utils::has_pro(),
    //         'image' => esc_url( ELEMENTOR_ASSETS_URL . 'images/go-pro.svg' ),
    //         'image_alt' => esc_attr__( 'Upgrade', 'textdomain' ),
    //         'title' => esc_html__( 'Promotion heading', 'textdomain' ),
    //         'description' => esc_html__( 'Get the premium version of the widget and grow your website capabilities.', 'textdomain' ),
    //         'upgrade_url' => esc_url( 'https://example.com/upgrade-to-pro/' ),
    //         'upgrade_text' => esc_html__( 'Upgrade Now', 'textdomain' ),
    //     ];
    // }
        
    public function get_style_depends() {
        return [ 'swiper' ];
    }

    public function get_script_depends() {
        return [ 'swiper' ];
    }
    
    protected function _get_all_products(){
        $get_products = new WC_Product_Query( array(
            'limit' => -1,
            'return' => 'ids',
        ) );

        $products = $get_products->get_products();

        if ( empty($products) ) {
            return;
        }

        $all_products = [];

        foreach ($products as $item) {
            $all_products[$item] = get_the_title( $item );
        }

        return $all_products;
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'addoncraft-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_feature_image',
            [
                'label' => esc_html__( 'Feature Image', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'textdomain' ),
                'label_off' => esc_html__( 'Hide', 'textdomain' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'product_list',
            [
                'label' => esc_html__( 'Product Details', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'list_product_details',
                        'label' => esc_html__( 'Details', 'textdomain' ),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'options' => [
                            '' => esc_html__( 'None', 'textdomain' ),
                            'title' => esc_html__( 'Product Title', 'textdomain' ),
                            'short_description' => esc_html__( 'Product Short Description', 'textdomain' ),
                            'price' => esc_html__( 'Product Price', 'textdomain' ),
                            // 'stock' => esc_html__( 'Product Stock', 'textdomain' ),
                            'product_description' => esc_html__( 'Product Content', 'textdomain' ),
                            // 'product_categories' => esc_html__( 'Product Categories', 'textdomain' ),
                            // 'product_tags' => esc_html__( 'Product Tags', 'textdomain' ),
                            // 'product_brands' => esc_html__( 'Product Brands', 'textdomain' ),
                            'product_atc' => esc_html__( 'Add to cart', 'textdomain' ),
                        ],
                        'default' => '',
                        'label_block' => true,
                    ],
                    [
                        'name' => 'product_content_limit',
                        'label' => esc_html__( 'Content Limit (words)', 'textdomain' ),
                        'type' => \Elementor\Controls_Manager::NUMBER,
                        // 'label_block' => true,
                        'min' => 5,
                        'max' => 200,
                        'step' => 1,
                        'default' => 30,
                        'condition' => [
                            'list_product_details' => ['product_description', 'short_description'],
                        ],
                    ],
                ],
                'default' => [
                    [
                        'list_product_details' => esc_html__( 'Title', 'textdomain' ),
                    ],
                ],
                // 'title_field' => '{{{ list_product_details }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'query_section',
            [
                'label' => esc_html__('Query', 'addoncraft-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'content_source',
            [
                'label' => esc_html__( 'Source', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'latest_products',
                'options' => [
                    'latest_products' => esc_html__( 'Latest Products', 'textdomain' ),
                    'manually_selection' => esc_html__( 'Manually Selection', 'textdomain' ),
                ],
            ]
        );

        $this->add_control(
            'manually_product',
            [
                'label' => esc_html__( 'Search & Select', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'options' => $this->_get_all_products(),
                'condition' => [
                    'content_source' => [ 'manually_selection' ],
                ],
            ]
        );

        $this->add_control(
            'product_order_by',
            [
                'label' => esc_html__( 'Order By', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'date'  => esc_html( 'Date' ),
                    'title'  => esc_html( 'Title' ),
                    'menu_order'  => esc_html( 'Menu Order' ),
                    'rand'  => esc_html( 'Random' ),
                ],
                'default' => 'date',
            ]
        );

        $this->add_control(
            'product_order',
            [
                'label' => esc_html__( 'Order', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'asc'  => esc_html( 'ASC' ),
                    'desc'  => esc_html( 'DESC' ),
                ],
                'default' => 'asc',
            ]
        );        

        $this->end_controls_section();   

        $this->start_controls_section(
            'ACE_section_general_style',
            [
                'label' => esc_html__('General', 'addoncraft-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'box_content_aligement',
            [
                'label' => esc_html__( 'Content Alignment', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => __('Start', 'text-domain'),
                        'icon' => 'eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => __('Center', 'text-domain'),
                        'icon' => 'eicon-align-center-v',
                    ],
                    'end' => [
                        'title' => __('End', 'text-domain'),
                        'icon' => 'eicon-align-end-v',
                    ],
                    
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .your-class' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'box_gap',
            [
                'label' => esc_html__( 'Gap', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 5,
                'max' => 100,
                'step' => 1,
                'default' => 10,
            ]
        );

        $this->end_controls_section();  

        $this->start_controls_section(
            'ACE_product_image_style',
            [
                'label' => esc_html__('Image', 'addoncraft-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'box_image_position',
            [
                'label' => esc_html__( 'Image Position', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'text-domain'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'top' => [
                        'title' => __('Top', 'text-domain'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'right' => [
                        'title' => __('Right', 'text-domain'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    
                ],
                'default' => 'right',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .your-class' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'image_width',
            [
                'label' => esc_html__( 'Width', 'textdomain' ),
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
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .your-class' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();  

        $this->start_controls_section(
            'ACE_product_slider_title_style',
            [
                'label' => esc_html__('Title', 'addoncraft-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .your-class',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .your-class' => 'color: {{VALUE}}',
                ],
                'global' => [
                    'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ACE_product_slider_description_style',
            [
                'label' => esc_html__('Content', 'addoncraft-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .your-class',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Color', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .your-class' => 'color: {{VALUE}}',
                ],
                'global' => [
                    'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ACE_product_slider_price_style',
            [
                'label' => esc_html__('Price', 'addoncraft-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} .your-class',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => esc_html__( 'Color', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .your-class' => 'color: {{VALUE}}',
                ],
                'global' => [
                    'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ACE_product_slider_atc_style',
            [
                'label' => esc_html__('Add to Cart', 'addoncraft-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'atc_typography',
                'selector' => '{{WRAPPER}} .your-class',
            ]
        );

        $this->add_control(
            'atc_color',
            [
                'label' => esc_html__( 'Color', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .your-class' => 'color: {{VALUE}}',
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
                'selector' => '{{WRAPPER}} .your-class',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'atc_border',
                'selector' => '{{WRAPPER}} .your-class',
            ]
        );

        $this->add_control(
            'atc_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'textdomain' ),
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
                    '{{WRAPPER}} .your-class' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'atc_padding',
            [
                'label' => esc_html__( 'Padding', 'textdomain' ),
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
                    '{{WRAPPER}} .your-class' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>

        <div class="ace-product-slider">
            <div class="ace-product-slider-wrapper ace-container-inner">
                <div class="ace-flex-column">
                    <div class="ace-column-wrapper">
                        <h2>Side Chair.</h2>
                        <div class="product-excerpt">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</p>                            
                        </div>
                        <div class="ace_atc">
                            <p>Price</p>
                            <button>Add to cart</button>
                        </div>
                    </div>
                </div>
                <div class="ace-flex-column">
                    <div class="ace-column-wrapper">
                        <div class="ace-product-img">
                            <img src="https://woodmart.xtemos.com/wp-content/uploads/2024/02/slider-main-demo-2-light-opt.jpg.webp">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php 
    }
    
    protected function content_template() {}    
}