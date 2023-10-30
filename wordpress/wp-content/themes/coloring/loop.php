<?php if ( is_front_page() && is_home() && !is_paged() && $wp_query->current_post == 0) : ?>    
  <article class="archive__item">
    <div class="archive__item-thumbnail">
      <a href="<?php the_permalink(); ?>">
        <?php if(has_post_thumbnail()):?> 
          <?php the_post_thumbnail('', array('class' => 'archive__img'));?>
        <?php else: ?>
          <?php echo '<img src="'.get_template_directory_uri().'/img/archive/img_no.jpg" alt="NO IMAGE" class="archive__img"/>';?>
        <?php endif;?>
      </a>
    </div>
    <div class="archive__lead">
      <h3 class="archive__ttl--mid">
        <a class="archive__ttl-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      </h3>    
      <?php if (get_post_type($post->ID) == 'post') :?>
        <?php if(has_category()):?>
          <ul class="archive__date--top-mid">
            <li class="archive__date-list-item--cat">
              <?php the_category(' ');?>
            </li>
            <li class="archive__date-list-item--time">
            <?php the_modified_date('Y/m/d') ?>
            </li>
          </ul>
        <?php endif;?>
      <?php endif; ?>
      <a href="<?php the_permalink(); ?>" class="archive__link-triangle"><span class="archive__link-txt">check</span></a>
    </div>  
  </article>  
<?php else:?>
  <?php if(!is_home() &&  $wp_query->current_post === 0): ?>
  <section class="section">
  <?php endif;?>
    <article class="archive__item-flex<?php if(is_home() && !is_paged()&& $wp_query->current_post === 1 || $wp_query->current_post == 0){echo '--first';}else{echo '--notfirst';}?>">
      <div class="archive__thumbnail-left">
        <a href="<?php the_permalink(); ?>">
          <?php if(has_post_thumbnail()):?> 
            <?php the_post_thumbnail('post-list', array('class' => 'archive__img'));?>
          <?php else: ?>
            <?php echo '<img src="'.get_template_directory_uri().'/img/archive/thumnail_min.jpg" alt="NO IMAGE" class="archive__img"/>';?>
          <?php endif;?>
        </a>
      </div>
      <div class="archive__lead-right">
        <h3 class="archive__ttl--large">
              <a href="<?php the_permalink(); ?>" class="archive__ttl-link"><?php the_title(); ?></a>
        </h3>
        <p class="archive__txt">
          <?php echo get_post_meta(get_the_ID(), '_aioseop_description', true); ?>
        </p>
        <?php if (get_post_type($post->ID) == 'post') :?>
          <?php if(has_category()):?>
            <ul class="archive__date--top-mid">
              <li class="archive__date-list-item--cat">
                <?php the_category(' > ','multiple');?>
              </li>
              <li class="archive__date-list-item--time">
              <?php the_modified_date('Y/m/d') ?>
              </li>
            </ul>
          <?php endif;?>
        <?php endif; ?>
        <a href="<?php the_permalink(); ?>" class="archive__link-vector">
            <img src="<?php echo get_template_directory_uri() ?>/img/button/link_icon--middle.png" width="19px" height="8px" class="link__icon">
        </a>
      </div>  
    </article>
<?php endif;?>