<?php get_header(); ?>
<div class="wrapper--large">
  <main class="main--middle">
    <header class="ttl-group--center">
      <h1 class="ttl-group__txt-en--404"><?php echo coloring_archive_title()['title'];?></h1>
      <p class="ttl-group__txt-ja--large" role="doc-subtitle"><?php echo coloring_archive_title()['subtitle'];?></p>
    </header>
    <div class="content__txt-outer--large">
     <p class="content__txt--center">
       アクセスしようとしたページは見つかりません。<br>削除もしくは移動された可能性があります。<br>よろしければ<?php if(is_mobile()){echo '下';}else{echo '左上';}?>の検索窓をご利用ください。
     </p> 
     <?php if(is_mobile()):?>  
        <div class="content__search--center">
          <?php get_search_form() ?>
        </div>
      <?php endif;?>
      <div class="archive__button-outer--center">
        <a href="/" class="archive__button-more--mid" role="button">TOPページへ</a>
      </div>
    </div>
  </main>
  <!-- l-sidebar -->
  <?php get_sidebar(); ?>
  <!-- end sidebar -->
</div>  
<?php get_footer(); ?>
