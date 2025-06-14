<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class ACE_Marquee extends \Elementor\Widget_Base {
    public function get_name() {
        return 'ACE_marquee';
    }

    public function get_title() {
        return esc_html__('Marquee', 'addoncraft-for-elementor');
    }

    public function get_icon() {
        return 'eicon-frame-expand';
    }
    
    public function get_categories() {
        return [ 'ACE_category_advanced' ];
    }

    public function get_keywords() {
        return ['marquee', 'infinite scroll', 'topbar'];
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
        return [
            'font-awesome-5-all',
            'font-awesome-4-shim',
        ];
    }

    public function get_script_depends() {
        return [
            'font-awesome-4-shim'
        ];
    }
    
    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'marquee_items',
            [
                'label' => esc_html__('Repeater Plan', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'marquee_text',
                        'label' => esc_html__('Text', 'addoncraft-for-elementor'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => esc_html__('List Title', 'addoncraft-for-elementor'),
                        'label_block' => true,
                    ],
                    [
                        'name' => 'marquee_icon',
                        'label' => esc_html__('Icon', 'addoncraft-for-elementor'),
                        'type' => \Elementor\Controls_Manager::ICONS,
                        'label_block' => true,
                    ],
                    [
                        'name' => 'marquee_image',
                        'label' => esc_html__('Choose Image', 'addoncraft-for-elementor'),
                        'type' => \Elementor\Controls_Manager::MEDIA,
                        'default' => [
                            'url' => esc_url(\Elementor\Utils::get_placeholder_image_src()),
                        ],
                    ]
                ],
                'default' => [
                    [
                        'marquee_text' => esc_html__('Spring Clearance Event', 'addoncraft-for-elementor'),
                    ],
                ],
                'title_field' => '{{{ marquee_text }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ACE_section_general_style',
            [
                'label' => esc_html__('Settings', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ACE_general_gap',
            [
                'label' => esc_html__('Gap', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 48,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ACE_marquee_items' => 'gap: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ACE_section_padding_style',
            [
                'label' => esc_html__('Padding', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 20,
                    'right' => 0,
                    'bottom' => 20,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ACE-marquee' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ACE_group_background_color',
            [
                'label' => esc_html__('Background Color', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#fcffb2',
                'selectors' => [
                    '{{WRAPPER}} .ACE-marquee' => 'background-color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'ACE_autoplay_speed',
            [
                'label' => esc_html__('Autoplay Speed (s)', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 5,
                'max' => 100,
                'step' => 5,
                'default' => 15,
                'selectors' => [
                    '{{WRAPPER}} .ACE_marquee_inner' => '--em-marquee-speed: {{SIZE}}s',
                ],
            ]
        );

        $this->end_controls_section();  

        $this->start_controls_section(
            'ACE_marquee_heading_style',
            [
                'label' => esc_html__('Heading', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ACE_heading_color',
            [
                'label' => esc_html__('Color', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .ACE_marquee_item .marquee_heading' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_heading_typography',
                'selector' => '{{WRAPPER}} .ACE_marquee_item .marquee_heading',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ACE_marquee_icon_style',
            [
                'label' => esc_html__('Icon', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ACE_icon_color',
            [
                'label' => esc_html__('Color', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .ACE_marquee_item .ACE_icon i' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'ACE_icon_size',
            [
                'label' => esc_html__('Size', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ACE_marquee_item .ACE_icon svg' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ACE_marquee_item .ACE_icon svg' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section(); 

        $this->start_controls_section(
            'ACE_marquee_image_style',
            [
                'label' => esc_html__('Image', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ACE_image_width',
            [
                'label' => esc_html__('Width', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ACE_marquee_item .ACE_image img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();  
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty($settings['marquee_items']) ) {
            return;
        }
        
        if ( !empty($settings['ACE_autoplay_speed']) ) {
            $playspeed = $settings['ACE_autoplay_speed'];
        } else {
            $playspeed = 1;            
        }

        ?>
        <div class="ACE-marquee">
            <div class="ACE_marquee_inner">
                <div class="ACE_marquee_items" data-playspeed="<?php echo esc_attr( $playspeed ); ?>">
                    <?php 
                        foreach ($settings['marquee_items'] as $item): 
                        $image_id = isset($item['marquee_image']['id']) ? absint($item['marquee_image']['id']) : 0;
                    ?>
                        <div class="ACE_marquee_item">
                            <span class="ACE_image">
                                <?php echo wp_kses_post(wp_get_attachment_image( $image_id, 'full' )); ?>
                            </span>
                            <span class="ACE_icon">
                                <?php if (!empty($item['marquee_icon']['value'])): ?>
                                    <?php \Elementor\Icons_Manager::render_icon( $item['marquee_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                <?php endif; ?>
                            </span>
                            <span class="marquee_heading"><?php echo esc_html($item['marquee_text']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div> 
            </div>    
        </div>
        <?php 
    }
    
    protected function content_template() {}    
}