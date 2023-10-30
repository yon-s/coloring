<?php if(is_single()){
  $sidebar_type = '--min';
}elseif(is_home()){
  $sidebar_type = '--index-middle';
}else{
  $sidebar_type = '--middle';
}
?>
<div class="sidebar<?php echo $sidebar_type;?>">
      <aside class="sidebar__outer">
        <div class="sidebar__ttl-group">
          <h2 class="sidebar__ttl-en">
            RANKING
          </h2>
          <p class="sidebar__ttl-ja">ランキング</p>
        </div>
        <?php //ランキング
        $ranks = array(
          'meta_key'=> 'post_views_count',
          'orderby' => 'meta_value_num',
          'order' => 'DESC',
          'posts_per_page' => 3,
        );
        $my_query = new WP_Query( $ranks  );?>
        <div class="sidebar__contets">
          <ol class="sidebar-rank__list">
            <?php
            $rankNo = 0;
            while ( $my_query->have_posts() ) : $my_query->the_post();
            $rankNo++ ?>
              <li class="sidebar-rank__list-item">
                <span class="sidebar-rank__no"><?php echo $rankNo;?></span>
                <div class="sidebar-rank__archive<?php echo $sidebar_type;?>">
                  <div class="sidebar-rank__thumbnail">
                    <a href="<?php the_permalink(); ?>">
                      <?php if(has_post_thumbnail()):?> 
                        <?php the_post_thumbnail('middle', array('class' => 'archive__img'));?>
                      <?php else: ?>
                        <?php echo '<img src="'.get_template_directory_uri().'/img/img_no.png" alt="NO IMAGE"/>';?>
                      <?php endif;?>
                    </a> 
                  </div>
                  <div class="sidebar-rank__lead">
                    <h3 class="sidebar-rank__ttl<?php echo $sidebar_type;?>">
                      <a href="<?php the_permalink(); ?>" class="sidebar-rank__link"><?php the_title(); ?></a>
                    </h3>
                      <?php if (get_post_type($post->ID) == 'post') :?>
                        <?php if(has_category()):?>
                          <p class="sidebar-rank__cat">
                            <?php the_category(' ');?>
                          </p>
                        <?php endif;?>
                      <?php endif;?>
                  </div>
                </div>
              </li>
            <?php endwhile; wp_reset_postdata();?>
          </ol>
        </div>
      </aside>
  </div>