<!--footer-->
<footer class="footer">
<div class="footer__breadcrumb<?php if(is_single()){echo '--single';}else{echo '--page';}?>">
<?php breadcrumb(); ?>
</div>
<?php
  $pickup = array(
    'post_type' => 'post',
    'order' => 'DESC',
    'posts_per_page' => 1,
    'category_name' => 'pickup'
  );
  $pickup_query = new WP_Query( $pickup  );
?>  
<?php if ($pickup_query->have_posts()) : ?>
  <div class="pickup">
      <h3 class="pickup__ttl--center">今週の気になるもの</h3>
    <div class="pickup__inner">
      <div class="pickup__content-outer">
        <div class="pickup__content">
        <?php 
        $count_posts = get_queried_object();
        $postsCount = $count_posts->count;
        $vol = $pickup_query->found_posts;//vol
        while ( $pickup_query->have_posts() ) : $pickup_query->the_post();
        ?>  
          <p class="pickup__vol">vol<span class="pickup__vol-bottom"><?php echo sprintf('%02d', $vol); ?></span></p>
          <div class="pickup__img-outer"><?php the_field('pickup_img'); ?></div>
          <?php $pickup_text = get_field('post_desc'); 
          //追加するclass名
          $addClassPickups = array('p' => 'pickup__text');
          foreach($addClassPickups as $htmlTag => $addClassPickup){
            if(strpos($pickup_text,'<'.$htmlTag.'>')!== false){
              $pickup_text = str_replace('<'.$htmlTag.'>','<'.$htmlTag.' class="'.$addClassPickup.'">', $pickup_text);
            }
          }
          echo $pickup_text;?>
          <?php $have_child_filed ='pickup_link';
          $have_parents = array_keys(get_field($have_child_filed));?>
          <?php
          $have_parent_issets = array();
          $values_isset_btn_txts = array();
          $btn_count = -1;
          foreach($have_parents as $have_parent){?>
            <?php $btn_count++;
            if(get_field($have_child_filed)[$have_parent]){
              array_push($have_parent_issets,get_field($have_child_filed)[$have_parent]);//リンク値
              array_push($values_isset_btn_txts,buy_btn_txt()[$btn_count]);//リンク値が入っているボタン
            }
          }?>
        </div>
        <?php $values_btn_count = -1;?>
            <?php foreach($have_parent_issets as $have_parent_isset):
              $values_btn_count++;?>
              <?php if($have_parent_isset === reset($have_parent_issets)){
                echo '<ul class="buy__button--flex">';
              }?>
                <li class="buy__button-link--flex" role="button"><a href="<?php echo $have_parent_isset;?>" target="_blank" role="noopener noreferrer" class="buy__link"><?php echo $values_isset_btn_txts[$values_btn_count];?></a></li>
              <?php if($have_parent_isset === end($have_parent_issets)){
                echo '</ul>';
              }?>  
          <?php endforeach;?>  
        <?php endwhile;wp_reset_query();?>  
      </div>    
    </div>  
  </div>  
<?php endif;?>  
  <div class="footer__bottom">
    <?php coloring_sns('footer'); ?>
      <?php  page_link('footer__link',get_option('coloring_footer_class'));?>
    <p class="footer__copy-right"><small>copyright 2018-<?php echo date('Y').' '.get_bloginfo('name').' ';?> all rights reserved</small></p>
    <?php if(get_option('coloring_footer-caution')):?>
      <p class="footer__caution"><small><?php echo get_option('coloring_footer-caution');?></small></p>
    <?php endif;?>
  </div>
</footer>
<!--end-footer-->
</body>
</html>