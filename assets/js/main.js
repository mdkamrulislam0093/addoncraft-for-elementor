jQuery(document).ready(function($){
  
  /*
  * Start Marquee
  */
  jQuery.map($('.ACE-marquee'), function( ace_marquee, index ){
    let ace_marquee_items = $('.ACE-marquee').eq(index).find('.ACE_marquee_items');
    let marquee_speed = ace_marquee_items.attr('data-playspeed');
    let marquee_offsetwidth = ace_marquee_items.width();
    let marquee_scrollWidth = ace_marquee.scrollWidth;
    let position = 0;
    var current_width = ((marquee_scrollWidth - marquee_offsetwidth) / 2) + marquee_offsetwidth;
  
    function animate() {
      position -= marquee_speed;

      if (position < -current_width )  {
        position = current_width;
      }
      
      ace_marquee_items.css('transform', `translateX(${position}px)`);
      requestAnimationFrame(animate);
    }
    
    animate();
  });
  /*
  * Start Marquee
  */
  
});