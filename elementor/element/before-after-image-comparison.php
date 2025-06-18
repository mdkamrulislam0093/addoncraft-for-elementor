<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class BeforeAfterImageComparison extends \Elementor\Widget_Base {
    public function get_name() {
        return 'ACE_before_after_image_comparison';
    }

    public function get_title() {
        return esc_html__('Before/After Image Comparison', 'addoncraft-for-elementor');
    }

    public function get_icon() {
        return 'eicon-image-before-after';
    }
    
    public function get_categories() {
        return [ 'ACE_category_advanced' ];
    }

    public function get_keywords() {
        return ['before and after image', 'block', 'image compare', 'image comparison', 'image filter'];
    }

    public function get_style_depends() {
        return [
            'ACE_before_after_image_script'
        ];
    }
     public function get_script_depends() {
        return [
            'ace_after-before-image-style'
        ];
    }
    
    
    protected function _register_controls() {
        $this->start_controls_section(
            'before_image_content_section',
            [
                'label' => esc_html__('Before', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ace_before_image',
            [
                'label' => esc_html__( 'Choose Before Image', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
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
                    '{{WRAPPER}} .ace-image-before .ace-image-inner' => 'background-size: {{VALUE}};',
                ],
            ]
        );


        $this->add_control(
            'ace_before_image_position',
            [
                'label' => esc_html__( 'Image Position', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'center center',
                'options' => [
                    "center center" => "Center Center",
                    "center left" => "Center Left",
                    "center right" => "Center Right",
                    "top center" => "Top Center",
                    "top left" => "Top Left",
                    "top right" => "Top Right",
                    "bottom center" => "Bottom Center",
                    "bottom left" => "Bottom Left",
                    "bottom right" => "Bottom Right",
                ],
                'selectors' => [
                    '{{WRAPPER}} .ace-image-before .ace-image-inner' => 'background-position: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'after_image_content_section',
            [
                'label' => esc_html__('After', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ace_after_image',
            [
                'label' => esc_html__( 'Choose After Image', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'ace_after_image_fit',
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
                    '{{WRAPPER}} .ace-image-after .ace-image-inner' => 'background-size: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ace_after_image_position',
            [
                'label' => esc_html__( 'Image Position', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'center center',
                'options' => [
                    "center center" => "Center Center",
                    "center left" => "Center Left",
                    "center right" => "Center Right",
                    "top center" => "Top Center",
                    "top left" => "Top Left",
                    "top right" => "Top Right",
                    "bottom center" => "Bottom Center",
                    "bottom left" => "Bottom Left",
                    "bottom right" => "Bottom Right",
                ],
                'selectors' => [
                    '{{WRAPPER}} .ace-image-after .ace-image-inner' => 'background-position: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->register_box_controls();
        $this->register_navigation_style_controls();
    }


    /**
     * Register Box Section controls
     */
    private function register_box_controls() {
        $this->start_controls_section(
            'ACE_BF_box_style',
            [
                'label' => esc_html__('Box', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_control(
            'ace_bf_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .ace-image-comparison' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'ace_bf_box_shadow',
                'selector' => '{{WRAPPER}} .ace-image-comparison',
            ]
        );

        $this->add_control(
            'ace_bf_box_height',
            [
                'label' => esc_html__( 'Height', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 600,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ace-image-wrapper' => 'height: {{SIZE}}{{UNIT}};',
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
            'ACE_BF_navigation_style',
            [
                'label' => esc_html__('Navigation', 'addoncraft-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,              
            ]
        );

        $this->add_control(
            'ace_bf_line_width',
            [
                'label' => esc_html__( 'Line Width', 'textdomain' ),
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
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ace-bf-slider-line' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'navigation_background',
                'types' => [ 'classic' ],
                'exclude' => ['image'],
                'default' => '#ffffff',
                'selector' => '{{WRAPPER}} .ace-bf-slider-line, {{WRAPPER}} .ace-bf-slider-button',
            ]
        );

        $this->add_control(
            'navigation_color',
            [
                'label' => esc_html__( 'Color', 'addoncraft-for-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .ace-bf-slider-button svg' => 'color: {{VALUE}}'
                ],
            ]
        );


        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

       // Sanitize and validate image IDs
        $before_image_id = !empty($settings['ace_before_image']['id']) ? absint($settings['ace_before_image']['id']) : 0;
        $after_image_id = !empty($settings['ace_after_image']['id']) ? absint($settings['ace_after_image']['id']) : 0;
        
        if ( empty($before_image_id) || empty($after_image_id) ) {
            return;
        }

        // Get images with error handling
        $before_image = wp_get_attachment_image_url( $before_image_id, 'full' );
        $after_image = wp_get_attachment_image_url($after_image_id, 'full' );

    ?>
    <div class="ace-image-comparison" role="img" aria-label="Image comparison slider">
        <div class="ace-image-wrapper">
            <div class="ace-image-before" aria-label="Before image">
                <div class="ace-image-inner" style="background-image: url( <?php echo esc_attr( $before_image ); ?> );"></div>
            </div>
            <div class="ace-image-after" aria-label="After image">
                <div class="ace-image-inner" style="background-image: url( <?php echo esc_attr( $after_image ); ?> );"></div>
            </div>
        </div>
        <input type="range" class="ace-bf-slider" min="0" max="100" value="50">
        <div class="ace-bf-slider-line" aria-hidden="true"></div>
        <div class="ace-bf-slider-button" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </div>
    </div>
<?php 
    }
    
    protected function content_template() {
        ?>
        <#
        var ace_before_image = settings.ace_before_image.url;
        var ace_after_image = settings.ace_after_image.url;
        #>
        <div class="ace-image-comparison">
            <div class="ace-image-wrapper">
                <div class="ace-image-before">
                    <div class="ace-image-inner" style="background-image: url( {{ _.escape(ace_before_image) }} );"></div>
                </div>
                <div class="ace-image-after" style="clip-path: inset(0px 0px 0px 50%); ">
                    <div class="ace-image-inner" style="background-image: url( {{ _.escape(ace_after_image) }} );"></div>                
                </div>
            </div>
            <input type="range" class="ace-bf-slider" min="0" max="100" value="50">
            <div class="ace-bf-slider-line"></div>
            <div class="ace-bf-slider-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </div>
        <?php 
    }    
}