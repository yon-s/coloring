<?php get_header(); ?>
<div class="wrapper--large">
  <main class="main--middle">
    <?php if (is_category() || is_tag() || is_author()|| is_year() || is_month() || is_day()):?>
      <header class="ttl-group">
            <h1 class="ttl-group__txt-en"><?php echo coloring_archive_title()['title'];?></h1>
            <p class="ttl-group__txt-ja--min" role="doc-subtitle"><?php echo coloring_archive_title()['subtitle'];?></p>
      </header>
    <?php endif;?>
    <?php if (have_posts()) : $count = 1; ?>
      <div class="archive">
        <?php while (have_posts()) : the_post(); ?>
          <?php get_template_part('loop');?>
        <?php endwhile;?>
        <?php if (function_exists('coloring_posts_pagination')) {
          coloring_posts_pagination($additional_loop->max_num_pages);
        }
        ?>
      </div>
    <?php else : ?>
      <div class="archive">
        <p class="content__txt">投稿が1件も見つかりませんでした。</p>
      </div>
    <?php endif;?>
  </main>
   <!-- l-sidebar -->
   <?php get_sidebar(); ?>
  <!-- end sidebar -->
</div>  
<?php get_footer(); ?>