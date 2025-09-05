<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class ACE_Logo_Infinite_Carousel extends \Elementor\Widget_Base {
    public function get_name() {
        return 'ACE_Logo_Infinite_Carousel';
    }

    public function get_title() {
        return esc_html__('Logo Carousel', 'addoncraft-for-elementor');
    }

    public function get_icon() {
        return 'eicon-carousel-loop';
    }
    
    public function get_categories() {
        return [ 'ACE_category_advanced' ];
    }


    public function get_keywords() {
        return [ 'logo carousel', 'carousel', 'loop' ];
    }

    public function get_style_depends() {
        return [
            'ACE_logo_carousel', 'swiper'
        ];
    }
     public function get_script_depends() {
        return [
            'ace_logo_carousel_script', 'swiper'
        ];
    }    
    
    protected function _register_controls() {
        $this->start_controls_section(
            'ace_content_section',
            [
                'label' => esc_html__('Content', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ace_logo_list',
            [
                'label' => esc_html__( 'Logos', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'logo_image',
                        'label' => esc_html__( 'Logo', 'addoncraft-for-elementor' ),
                        'type' => \Elementor\Controls_Manager::MEDIA,
                        'default' => [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                        'label_block' => true,
                    ],
                    [
                        'name' => 'logo_heading',
                        'label' => esc_html__( 'Heading', 'addoncraft-for-elementor' ),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => '',
                        'label_block' => true,
                        'dynamic' => [
                            'active' => true,
                        ],
                    ],
                    [
                        'name' => 'ace_logo_url',
                        'label' => esc_html__( 'Logo URL', 'addoncraft-for-elementor' ),
                        'type' => \Elementor\Controls_Manager::URL,
                        'options' => [ 'url', 'is_external', 'nofollow' ],
                        'default' => [
                            'url' => '',
                            'is_external' => true,
                            'nofollow' => true,
                        ],
                        'label_block' => true,
                        'dynamic' => [
                            'active' => true,
                        ],                        
                    ]
                    
                ],
                'title_field' => '{{{ logo_heading }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ace_carousel_settings',
            [
                'label' => __('Carousel Settings', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'ace_slides_to_show',
            [
                'label' => __('Slides to Show', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 4,
                'tablet_default' => 3,
                'mobile_default' => 2,
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'ace_slides_between',
            [
                'label' => __('Gap Between Slides', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 15,
                 'frontend_available' => true,
            ]
        );


        $this->add_responsive_control(
            'ace_slides_speed',
            [
                'label' => __('Speed', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1000,
                'max' => 15000,
                'step' => 100,
                'default' => 5000,
                'frontend_available' => true,
            ]
        );


        $this->end_controls_section();

        // Style Tab - Logo Styles
        $this->start_controls_section(
            'ace_logo_style',
            [
                'label' => __('Item Styles', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ace_item_justify',
            [
                'label' => esc_html__( 'Content Alignment', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Start', 'addoncraft-for-elementor'),
                        'icon' => 'eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => __('Center', 'addoncraft-for-elementor'),
                        'icon' => 'eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => __('End', 'addoncraft-for-elementor'),
                        'icon' => 'eicon-justify-end-h',
                    ],
                    
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ace-lg-slide-wrap a' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .ace-lg-slide-wrap .ace-lg-title h4' => 'text-align: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_section();


        // Style Tab - Logo Styles
        $this->start_controls_section(
            'ace_logo_st',
            [
                'label' => __('Logo', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'ace_logo_height',
            [
                'label' => __('Logo Height', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 300,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 20,
                        'step' => 0.1,
                    ],
                    'rem' => [
                        'min' => 1,
                        'max' => 20,
                        'step' => 0.1,
                    ],
                    'vh' => [
                        'min' => 5,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 80,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ace-lg-slide-logo img' => 'height: {{SIZE}}{{UNIT}}; width: auto;',
                ],
            ]
        );

        $this->add_control(
            'ace_before_image_fit',
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
                    '{{WRAPPER}} .ace-lg-slide-logo img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ace_logo_after_gap',
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
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ace-lg-slide-logo' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ace_logo_padding',
            [
                'label' => __('Logo Padding', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ace-lg-slide-logo' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        ); 

        $this->start_controls_tabs(
            'ace_logo_normal_tabs'
        );

        $this->start_controls_tab(
            'ace_logo_background_tab',
            [
                'label' => esc_html__( 'Normal', 'addoncraft-for-elementor' ),
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'ace_logo_background',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .ace-lg-slide-logo',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'ace_logo_border',
                'label' => __('Border', 'addoncraft-for-elementor'),
                'selector' => '{{WRAPPER}} .ace-lg-slide-logo',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'ace_logo_box_shadow',
                'label' => esc_html__('Box Shadow', 'addoncraft-for-elementor'),
                'selector' => '{{WRAPPER}} .ace-lg-slide-logo',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'ace_logo_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'addoncraft-for-elementor' ),
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'ace_logo_background_hover',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .ace-lg-slide-logo:hover',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'ace_logo_border_hover',
                'label' => __('Border', 'addoncraft-for-elementor'),
                'selector' => '{{WRAPPER}} .ace-lg-slide-logo:hover',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'ace_logo_box_shadow_hover',
                'label' => esc_html__('Box Shadow', 'addoncraft-for-elementor'),
                'selector' => '{{WRAPPER}} .ace-lg-slide-logo:hover',
            ]
        );

        $this->add_control(
            'ace_logo_hover_transition',
            [
                'label' => esc_html__('Transition Duration (ms)', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 3000,
                        'step' => 50,
                    ],
                ],
                'default' => [
                    'size' => 300,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ace-lg-slide-logo' => 'transition: all {{SIZE}}ms ease-in-out;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->add_control(
            'ace_logo_border_radius',
            [
                'label' => __('Border Radius', 'addoncraft-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ace-lg-slide-logo' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'ace_logo_title_style',
            [
                'label' => __('Title', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'ace_title_typography',
                'selector' => '{{WRAPPER}} .ace-lg-title h4, {{WRAPPER}} a',
            ]
        );

        $this->start_controls_tabs(
            'ace_title_normal_tabs'
        );

            $this->start_controls_tab(
                'ace_title_color_tab',
                [
                    'label' => esc_html__( 'Normal', 'addoncraft-for-elementor' ),
                ]
            );

            $this->add_control(
                'ace_title_color',
                [
                    'label' => esc_html__( 'Color', 'addoncraft-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ace-lg-title h4' => 'color: {{VALUE}}',
                        '{{WRAPPER}} a' => 'color: {{VALUE}}',
                    ],
                    'global' => [
                        'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->start_controls_tab(
                'ace_title_hover_tab',
                [
                    'label' => esc_html__( 'Hover', 'addoncraft-for-elementor' ),
                ]
            );

            $this->add_control(
                'ace_title_color_hover',
                [
                    'label' => esc_html__('Hover Color', 'addoncraft-for-elementor'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ace-lg-slide-wrap:hover .ace-lg-title' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .ace-lg-slide-wrap:hover .ace-lg-title h4' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .ace-lg-slide-wrap:hover a' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->end_controls_section();
    }



    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty($settings['ace_logo_list']) || !is_array($settings['ace_logo_list']) ) {
            return;
        }

        $slides_mobile = isset($settings['ace_slides_to_show_mobile']) ? absint($settings['ace_slides_to_show_mobile']) : 2;
        $slides_tablet = isset($settings['ace_slides_to_show_tablet']) ? absint($settings['ace_slides_to_show_tablet']) : 3;
        $slides_desktop = isset($settings['ace_slides_to_show']) ? absint($settings['ace_slides_to_show']) : 4;
        $slides_between = isset($settings['ace_slides_between']) ? absint($settings['ace_slides_between']) : 0;
        $slides_speed = isset($settings['ace_slides_speed']) ? absint($settings['ace_slides_speed']) : 1000;

        $swiper_settings = [
            'slidesPerView' => $slides_mobile,
            'spaceBetween' => $slides_between,
            'speed' => $slides_speed,
            'loop' => true,
            'allowTouchMove' => false,
            'autoplay' => [
                'delay' => 0,
                'disableOnInteraction' => false,
            ],
            'breakpoints' => [
                '1024' => [
                    'slidesPerView' => $slides_desktop,
                ],
                '640' => [
                    'slidesPerView' => $slides_tablet,
                ],
            ],
        ];

        $unique_id = 'ace_lg_carousel_' . uniqid();

    ?>
    <style type="text/css">
        .swiper-wrapper {
            transition-timing-function: linear;
        }            
    </style>
      <div id="<?php echo esc_attr( $unique_id ); ?>" class="swiper ace_lg_carousel" data-settings='<?php echo esc_attr( wp_json_encode($swiper_settings) ); ?>'>
        <div class="swiper-wrapper">

          <?php 
            $logo_count = 0;
            $max_logos = 50;

          foreach ($settings['ace_logo_list'] as $index => $item): 
            if ($logo_count >= $max_logos) {
                break;
            }
            $logo_count++;
            
            // Validate item is array
            if (!is_array($item)) {
                continue;
            }

            $logo_img = '';

            if (isset($item['logo_image']) && is_array($item['logo_image']) && !empty($item['logo_image']['id'])) {
                $logo_id = absint($item['logo_image']['id']);
                if ($logo_id > 0) {
                    // Verify attachment exists and user can access it
                    $attachment = get_post($logo_id);
                    if ($attachment && $attachment->post_type === 'attachment') {
                        $logo_img = wp_get_attachment_image($logo_id, 'full', false, [
                            'loading' => 'lazy',
                            'decoding' => 'async'
                        ]);
                    }
                }
            }

            // Sanitize title
            $title = '';
            if ( !empty($item['logo_heading']) ) {
                $title = sanitize_text_field($item['logo_heading']);
            } 

            // Sanitize and validate URL
            $has_url = false;
            $link_key = 'item_ace_logo_url_' . $index;
            
            if (!empty($item['ace_logo_url']['url'])) {
                $url = esc_url_raw($item['ace_logo_url']['url']);
                if (!empty($url)) {
                    // Additional URL validation
                    $parsed_url = wp_parse_url($url);
                    if ($parsed_url && isset($parsed_url['scheme']) && in_array($parsed_url['scheme'], ['http', 'https'])) {
                        $this->add_link_attributes($link_key, $item['ace_logo_url']);
                        $has_url = true;
                    }
                }
            }

            ?>
            <div class="swiper-slide">
                <div class="ace-lg-slide-wrap">

                    <?php if ( $has_url ): ?>
                        <a <?php $this->print_render_attribute_string( $link_key ); ?>>
                    <?php endif ?>

                    <div class="ace-lg-slide-logo">
                        <?php if ( !empty($logo_img) ): 
                            echo wp_kses_post( $logo_img );
                        endif; ?>
                    </div>

                    <?php if (!empty($title)): ?>
                        <div class="ace-lg-title">
                            <h4><?php echo esc_html($title); ?></h4>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($has_url): ?>
                        </a>
                    <?php endif; ?>

                </div>
            </div>
          <?php endforeach ?>
        </div>
      </div>   

    <script type="text/javascript">
    jQuery(document).ready(function($){
        var carousel_id = <?php echo wp_json_encode('#' . $unique_id); ?>;
        var $carousel = $(carousel_id);
        
        if ($carousel.length && typeof Swiper !== 'undefined') {
            try {
                var carousel_settings = $carousel.data('settings');
                if (carousel_settings && typeof carousel_settings === 'object') {
                    var swiper = new Swiper(carousel_id, carousel_settings);
                }
            } catch (e) {
                console.error('Swiper initialization failed:', e);
            }
        }
    });
    </script>
<?php 
    }
    
    protected function content_template() {
        ?>
        <#
        if ( settings.ace_logo_list ) { 
            var column_width = 100 / parseInt(settings.ace_slides_to_show);
            var slides_between = parseInt(settings.ace_slides_between) || 0;
        #>
        <style type="text/css">
            .template_ace_lg_carousel .ace_content_wrapper {
                display: flex;
                gap: {{ slides_between }}px;
                overflow-x: auto;
            }
            .template_ace_lg_carousel .ace-content-item {
                width: {{ column_width }}%; 
                flex: 0 0 {{ column_width }}%; 
                min-width: {{ column_width }}%;
            }                     
        </style>
        <div class="template_ace_lg_carousel">
            <div class="ace_content_wrapper">

        <# _.each( settings.ace_logo_list, function( item, index ) { 
            var logo_img_url = item.logo_image && item.logo_image.url ? item.logo_image.url : '';
            var logo_heading = item.logo_heading || '';
            var logo_url = item.ace_logo_url && item.ace_logo_url.url ? item.ace_logo_url.url : '';

        // Basic client-side validation
            if (logo_img_url && !logo_img_url.match(/^https?:\/\/.+\.(jpg|jpeg|png|gif|svg|webp)$/i)) {
                logo_img_url = '';
            }
            if (logo_url && !logo_url.match(/^https?:\/\/.+/)) {
                logo_url = '';
            }            
        #>
            <div class="ace-content-item">
                <div class="ace-lg-slide-wrap">

                    <# if ( logo_url !== '' ) { #>
                        <a href="{{ logo_url }}" rel="noopener noreferrer">
                    <# } #>

                        <# if ( logo_img_url !== '' ) { #>
                            <div class="ace-lg-slide-logo">
                                <img src="{{ logo_img_url }}" alt="{{ logo_heading }}" loading="lazy">
                            </div>
                        <# } #>

                        <# if ( logo_heading !== '' ) { #>
                            <div class="ace-lg-title">
                                <h4>{{ logo_heading }}</h4>
                            </div>
                        <# } #>

                    <# if ( logo_url !== '' ) { #>                        
                        </a>
                    <# } #> 

                </div>
            </div>
        <# }); #>
            </div>
        </div>
        <# } #>

        <?php 
    }    
}