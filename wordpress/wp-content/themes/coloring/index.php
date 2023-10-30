<?php get_header(); ?>
<div class="wrapper--<?php if(!is_paged()){echo 'index';}else{echo 'large';}?>">
  <main class="main--middle">
  <?php 
  $posts_per_page = '';
  $loop_query = new WP_Query(exclusion_cat_ids($posts_per_page));?>
  <?php if ($loop_query->have_posts()) : ?>
    <div class="archive">
      <?php while ($loop_query->have_posts()) : $loop_query->the_post(); ?>
      <?php 
      $wp_query = $loop_query;?>
      <?php if($wp_query->current_post === 0):?>
      <?php endif;?>
      <?php if(!is_paged() && $wp_query->current_post === 1|| is_paged() && $wp_query->current_post === 0):?>
        <section class="section<?php if(!is_paged()){echo '--before-wideobj';}?>">
        <div class="ttl-group">
        <h2 class="ttl-group__txt-en">NEW POST</h2>
        <p class="ttl-group__txt-ja--min" role="doc-subtitle">最新記事</p>
      </div>  
      <?php endif;?>  
      <?php get_template_part('loop');?>
		  <?php endwhile; ?>
      <?php if(!is_paged()):?>
        <div class="archive__button-outer--center">
          <a href="/page/2/" class="archive__button-more--mid" role="button">もっとみる</a>
        </div>
      <?php else:?>
        <?php if (function_exists('coloring_posts_pagination')) {
          coloring_posts_pagination($additional_loop->max_num_pages);
        }?>
        <?php endif;?>
    </div> <!--end .archive-->
  <?php else : ?>
    <div class="archive">
        <p class="content__txt">投稿が1件も見つかりませんでした。</p>
      <?php wp_reset_query();?>
    </div>
  <?php endif; ?>
  <?php if(!is_paged()):?>
    <section class="section--wideobj-bottom">
      <div class="ttl-group">
        <h2 class="ttl-group__txt-en">SERIAL</h2>
        <p class="ttl-group__txt-ja--min" role="doc-subtitle">連載</p>
      </div>
      <ul class="cat-banner__list"> 
    <?php $all_categories = get_categories();
    foreach( $all_categories as $cat ) :  
    $cat_banner_img_file = get_stylesheet_directory().'/img/'.$cat->slug.'.jpg';
      if(file_exists($cat_banner_img_file)):?>
      <li class="cat-banner__list-item">
        <a href="<?php echo get_category_link( $cat->term_id ); ?>">
          <?php echo '<img src=" '.get_template_directory_uri().'/img/'.$cat->slug.'.jpg" alt="'.$cat->name.'" class="cat-banner__list-img" />';?>
        </a>
      </li>
      <?php endif;?>
    <?php endforeach; ?>
  </ul>
    </section>  
  <?php endif; ?>
  </main>
   <!-- l-sidebar -->
   <?php get_sidebar(); ?>
  <!-- end sidebar -->
</div><!--end wrapper-->
<?php get_footer(); ?>