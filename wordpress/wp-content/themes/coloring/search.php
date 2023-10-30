<?php get_header(); ?>
<div class="wrapper--large">
  <main class="main--middle">
    <header class="ttl-group">
      <h1 class="ttl-group__txt-en"><?php echo coloring_archive_title()['title'];?></h1>
        <p class="ttl-group__txt-ja--min" role="doc-subtitle"><?php echo coloring_archive_title()['subtitle'].'&#40;全'.$wp_query->found_posts;?>件&#41;</p>
    </header>
    <?php if (have_posts()) : ?>
      <div class="archive">
      <?php while (have_posts()) : the_post(); ?>
	      <?php get_template_part('loop'); ?>
	    <?php endwhile; ?>
      <?php if (function_exists('coloring_posts_pagination')) {
          coloring_posts_pagination($additional_loop->max_num_pages);
        }
        ?>
      </div>
    <?php else : ?>
      <div class="archive">
        <p class="content__txt">投稿が1件も見つかりませんでした。<br>よろしければ<?php if(is_mobile()){echo '下';}else{echo '左上';}?>の検索をご利用ください。</p>
      <?php if(is_mobile()):?>  
        <div class="content__search">
          <?php get_search_form() ?>
        </div>
      <?php endif;?>
      </div>
    <?php endif;?>
  </main>
  <!-- l-sidebar -->
  <?php get_sidebar(); ?>
  <!-- end sidebar -->  
</div>  
<?php get_footer(); ?>