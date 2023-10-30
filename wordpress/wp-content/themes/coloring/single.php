<?php 
//category
if(get_the_category()){
	$cat_meta = get_option("cat_meta_data");
	$cat = get_the_category();
	$cat_id   = $cat[0]->cat_ID;
	$cat_name = $cat[0]->cat_name;
	$cat_link = get_category_link($cat_id);
	$cat_post_num = $cat[0]->count;
}
//tag
if(get_the_tags()){
  $tags = get_the_tags();
}
//thumbnail
  //サイズの画像内容を取得
  $thumbnail_id = get_post_thumbnail_id(); 
  $icatch_img = wp_get_attachment_image_src( $thumbnail_id , 'large' );
  $icatch_srcset = wp_get_attachment_image_srcset( $thumbnail_id , 'large' );
  //画像出力
  $src = $icatch_img[0];
  $width = $icatch_img[1];
  $height = $icatch_img[2];
?>
<?php get_header(); ?>
<?php if($cat_id !== 137):?>
<!-- thumbnail -->
<div class="single__thumbnail">
<?php if(has_post_thumbnail()) : ?>
  <img src="<?php echo $src; ?>" srcset="<?php echo $icatch_srcset;?>" alt="<?php the_title(); ?> " width="<?php echo $width; ?>" height="<?php echo $height; ?>" class="single__thumbnail-img"/>
<?php else:?>
  <img src="<?php echo get_template_directory_uri(); ?>/img/archive/thumnail_large.jpg" alt="NO IMAGE" width="1280" height="675" class="single__thumbnail-img"/>  
<?php endif;?>
</div>
<!-- end-thumbnail -->
<?php endif;?>
<div class="wrapper--single">
  <main class="main--min">
  <?php breadcrumb(); ?>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <section class="content">
        <div class="content__lead">
          <div class="content__lead-top">
            <ul class="content__lead-date-list">
            <!-- category-->
              <li class="content__lead-list-item--cat">
                <a href="<?php echo $cat_link; ?>" class="content__lead-list-link"><?php echo $cat_name; ?></a></a>
              </li>
            <!-- end-category-->
              <li class="content__lead-list-item--time">
                <span class="content__time">公開日&#058;&nbsp;<?php the_time('Y/m/d'); ?></span>
                <?php if(get_the_time('Y/m/d') != get_the_modified_date('Y/m/d')):?>
                  <span class="content__time">更新日&#058;&nbsp;<?php the_modified_date('Y/m/d') ?></span>
                <?php endif;?>
              </li>
            </ul>  
          </div>
          <div class="content__lead-second">
            <!-- title-->
            <h1 class="content__ttl"><?php the_title(); ?></h1>
            <!-- end-title-->
          </div>  
          <?php if($tags) : ?>
            <div class="content__lead-bottom">
                <!-- tag-->
                <ul  class="content__lead-tag-list">
                <?php foreach($tags as $tag):?>
                  <li class="content__lead-list-item"><a href="<?php echo get_tag_link($tag->term_id);?>" class="content__lead-list-taglink"><?php echo $tag->name;?></a></li><!-- end-tag-->
                <?php endforeach;?>
                </ul>
            </div>
        <?php endif;?>
        </div>
        <div class="content__main">
        <?php $content_desc = get_field('post_desc');
          if($cat_id !== 137):
            if($content_desc):?>  
          <div class="content__info-desc">  
            <?php 
              //追加するclass名
              $addClassNmaes = array('p' => 'content__txt','img' => 'content__img','ul' => 'content__ul','ol' => 'content__ol','li' => 'content__li','blockquote' => 'content__blockquote','table' => 'content__table','tbody' => 'content__tbody','tr' => 'content__tr','th' => 'content__th','td' => 'content__td');
              foreach($addClassNmaes as $tag => $addClassNmae){
                if(strpos($content_desc,'<'.$tag.'>')!== false){
                  $content_desc = str_replace('<'.$tag.'>','<'.$tag.' class="'.$addClassNmae.'">', $content_desc);
                }elseif(strpos($content_desc,'<img ')!== false){
                  $content_desc = str_replace('<'.$tag,'<'.$tag.' class="'.$addClassNmae.'" ', $content_desc);
                }
              }
              echo $content_desc;
            ?>
          </div>
          <?php endif;?>
        <?php endif;?>
        <?php if($tags) : ?>
              <!-- タグごとに広告切り替え-->
              <?php foreach($tags as $tag):?>
                <?php
                $tag_slug = $tag->slug;
                if( $tag_slug === 'amazon'): //amazonタグで表示?>
                  <?php if($cat_id !== 137)://タグamazonへのおすすめ?>
                      <?php $pickup_link_url_item  = 'recommend_apps_for_firetvstick';?>
                      <?php if($pickup_link_url_item):?>
                        <?php if(!is_single($pickup_link_url_item)):?>
                          <?php $id = get_post_id_by_slug( $pickup_link_url_item );//投稿スラッグからidを取得?>
                          <?php //echo do_shortcode('[post_link '.get_permalink( $id ).']'); ?>
                        <?php endif;?>
                      <?php endif;?>
                  <?php endif;//end タグamazonへのおすすめ?>
                  <div class="content__add"><iframe src="https://rcm-fe.amazon-adsystem.com/e/cm?o=9&p=48&l=ur1&category=echo_show_15&banner=1JCF8X4MCGE4CAT54Z02&f=ifr&linkID=e34651f782c2ef06ab016376ef631ea9&t=iosuqsimfree-22&tracking_id=iosuqsimfree-22" width="100%" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0" sandbox="allow-scripts allow-same-origin allow-popups allow-top-navigation-by-user-activation"></iframe></div> 
                <?php endif;
              endforeach;?>
          <?php endif;?>
            <?php echo do_shortcode('[adsense]')?>
        <?php if($cat_id !== 137)://このサイトで人気の記事?>
            <?php $pickup_link_url_item  = get_option('coloring_pickup_link_url');?>
            <?php if($pickup_link_url_item):?>
              <?php if(!is_single($pickup_link_url_item)):?>
                <?php $id = get_post_id_by_slug( $pickup_link_url_item );//投稿スラッグからidを取得?>
                <div class="content__inside-ttl-outer"><p class="content__inside-ttl">このサイトで注目を集めている記事</p></div>
                <?php echo do_shortcode('[post_link '.get_permalink( $id ).']'); ?>
              <?php endif;?>
            <?php endif;?>
        <?php endif;//end このサイトで人気の記事?>
          <?php the_content(); ?>
          <?php foreach($tags as $tag):?>
                <?php
                $tag_slug = $tag->slug;
                  if( $tag_slug === 'uqmobile'):
                      if(!is_single('more_apple_than_uq')):?>
                    <?php echo do_shortcode('[post_link https://local.coloring.com/gadget/phone/more_apple_than_uq/]'); 
                      else:
                        echo do_shortcode('[post_link https://local.coloring.com/gadget/phone/sns/]');
                      endif;?>
                      <div class="content__add">
                        <p class="content__txt">
                          月¥1,628（税込）から利用でき、ネット割引で¥月990（税込）からになる格安SIM UQモバイル。<br>安定のau高速通信が使え快適に利用できます。
                          最大<span class="content__marker">¥13,000円相当</span>のau Pay残高がもらえるキャンペーンを実施中！<span class="caution__no">(※1)</span>
                          <br>気になる方はぜひ公式ホームページを↓</p>
                          <script language="javascript" src="//ad.jp.ap.valuecommerce.com/servlet/jsbanner?sid=3493125&pid=887792197"></script><noscript><a href="//ck.jp.ap.valuecommerce.com/servlet/referral?sid=3493125&pid=887792197" rel="nofollow"><img src="//ad.jp.ap.valuecommerce.com/servlet/gifbanner?sid=3493125&pid=887792197" border="0"></a></noscript>
                        <div class="content__txt">
                          <p class="caution__list-item">auまたはpovoからののりかえ除くSIM単体ご契約の方が対象。詳しくは<a href="https://shop.uqmobile.jp/shop/cashback/" class="content__link" rel="noopener">こちら</a></p>
                        </div>
                      </div>
                  <?php elseif( $tag_slug === 'ahamo'):?>
                    <div class="content__add"><p class="content__txt">
                          ahamoの<span class="content__marker">お申込み</span>はこちらから！
                          <br>↓↓↓↓↓↓</p>
                      <a href="https://h.accesstrade.net/sp/cc?rk=0100oro700maul" rel="nofollow" referrerpolicy="no-referrer-when-downgrade"><img src="https://h.accesstrade.net/sp/rr?rk=0100oro700maul" alt="ahamo新規・MNP" border="0" /></a>
                    </div>
                  <?php elseif( $tag_slug === 'ipad'):
                    if(!is_single('ipad_documents')):?>
                    <?php echo do_shortcode('[post_link https://local.coloring.com/gadget/ipad/ipad_documents/]'); ?>
                    <?php
                    else: 
                      echo do_shortcode('[post_link https://local.coloring.com/gadget/ipad/ipados134-mouse/]');
                    endif; 
                  endif; //end amazonタグで表示
              endforeach;?>
          <?php if($cat_post_num > 1):?>
          <p class="content__txt">このサイトでは、この他にも<?php echo $cat_name; ?>に関する記事が<?php echo $cat_post_num - 1; ?>件ございます。もし興味があればページ下にあるオススメ記事をご覧ください。<div class="content__link-btn-outer--center"><a href="#related" target="_blank" rel="noopener" class="content__link-btn">オススメの記事を見る</a></div></p>
          <?php endif;?>
          <?php if($tags) : ?>
              <!-- タグごとに広告切り替え-->
              <?php foreach($tags as $tag):?>
                <?php
                $tag_slug = $tag->slug;
                if( $tag_slug === 'amazon'): //amazonタグで表示?>
                    <?php if($cat_id !== 137)://タグamazonへのおすすめ?>
                        <?php $pickup_link_url_item  = 'recommend_apps_for_firetvstick';?>
                        <?php if($pickup_link_url_item):?>
                          <?php if(!is_single($pickup_link_url_item)):?>
                            <?php $id = get_post_id_by_slug( $pickup_link_url_item );//投稿スラッグからidを取得?>
                            <?php //echo do_shortcode('[post_link '.get_permalink( $id ).']'); ?>
                          <?php endif;?>
                        <?php endif;?>
                    <?php endif;//end タグamazonへのおすすめ?>
                      <div class="content__add"><iframe src="https://rcm-fe.amazon-adsystem.com/e/cm?o=9&p=294&l=ur1&category=echo_show_15&banner=18G71N6F3W6KYQ8VJ902&f=ifr&linkID=4f15a71eefcc2a53bc8aa94c1501040d&t=iosuqsimfree-22&tracking_id=iosuqsimfree-22" width="320" height="100" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0" sandbox="allow-scripts allow-same-origin allow-popups allow-top-navigation-by-user-activation"></iframe></div> 
                  <?php endif; //end amazonタグで表示
              endforeach;?>
          <?php endif;?>
          <?php echo do_shortcode('[adsense]')?>
        </div>
      </section>
      <?php endwhile; endif;?>
      <?php if($cat_id === 137):?>
        <!-- product --><aside><h3 class="content__ttl3--punctuation">この記事で紹介したもの</h3><div class="content__aside-scroll"><div class="product__aside-main--flex">
          <article class="product__list-item">
            <div class="product__img-outer">
              <?php the_field('pickup_img'); ?>
            </div>
            <div class="product__content">
              <div class="product__info">
                <p class="product__name"><?php the_field('pickup_name'); ?></p>
                <p class="product__price"><?php echo  '&yen;'.number_format(get_field('pickup_price'));?></p>
              </div>
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
              <?php $values_btn_count = -1;?>
              <?php foreach($have_parent_issets as $have_parent_isset):
                $values_btn_count++;?>
                <?php if($have_parent_isset === reset($have_parent_issets)){
                  echo '<ul class="buy__button--column">';
                }?>
                  <li class="buy__button-link--column" role="button"><a href="<?php echo $have_parent_isset;?>" target="_blank" role="noopener noreferrer" class="buy__link--border"><?php echo $values_isset_btn_txts[$values_btn_count];?></a></li>
                <?php if($have_parent_isset === end($have_parent_issets)){
                  echo '</ul>';
                }?>  
              <?php endforeach;?>  
            </div>
          </article>
        </div></div></aside>
      <?php else:?>  
        <?php 
        $filed_id_product = 3452;//カスタムフィールドグループのID
        $field_counter = acf_roop_display($filed_id_product)['field_counter'];
        ?>
        <?php for($field_count =0; $field_count < $field_counter; $field_count++):?>
          <?php 
          $field_name = acf_get_fields($filed_id_product)[$field_count]["name"];//フィールドを取得
          $next_field_name = acf_get_fields($filed_id_product)[$field_count+1]["name"];//フィールドを取得
          $firest_field = get_field($field_name)[acf_roop_display($filed_id_product)['field_parent_keys'][2]];//入力があるか確認する項目(product1->product__name)
          $next_field_check = get_field($next_field_name)[acf_roop_display($filed_id_product)['field_parent_keys'][2]];//次のフィールドに入力があるか確認する項目
          if($field_count === 0&&!empty($firest_field)){//一個目のフィールドの入力確認項目($firest_field )に入力があったら表示
            echo ' <!-- product --><aside><h3 class="content__ttl3--punctuation">この記事で紹介したもの</h3><div class="content__aside-scroll"><div class="product__aside-main--flex">';
          }
          ?>
          <?php if(!empty($firest_field)): //入力確認項目($firest_field )に入力があったらそのフィールドを表示
            $field_parent_keys_count=count(acf_roop_display($filed_id_product)['field_parent_keys']);?>
            <article class="product__list-item">
              <div class="product__img-outer">
                <?php $product_img = get_field($field_name)[acf_roop_display($filed_id_product)['field_parent_keys'][0]];
                if(strpos($product_img,'<a')!== false){
                  $product_img = str_replace('<img','<img class="product__img" ', $product_img);
                }else{
                  $product_img = '<img src="'.$product_img.'" width="228" height="312"  class="product__img"/>';
                }
                echo $product_img;
                ?>
                  <p class="product__img-figcaption"><?php echo  get_field($field_name)[acf_roop_display($filed_id_product)['field_parent_keys'][1]];?></p>
              </div>
              <div class="product__content"> 
                <div class="product__info">
                  <p class="product__name"><?php echo  get_field($field_name)[acf_roop_display($filed_id_product)['field_parent_keys'][2]];?></p>
                  <p class="product__price"><?php echo  '&yen;'.number_format(get_field($field_name)[acf_roop_display($filed_id_product)['field_parent_keys'][3]]);?></p>
                </div>
                <?php 
                $field_havechild_keys =get_field($field_name)[acf_roop_display($filed_id_product)['field_havechild_keys']];
                $field_have_child_last = acf_roop_display($filed_id_product)['field_child_keys'];
                $field_child_keys_count = count($field_have_child_last);
                $field_child_values_isset = array();
                $values_isset_btn_txts = array();?>
                <?php for($count_have_child_last = 0; $count_have_child_last < $field_child_keys_count ; $count_have_child_last++):?>
                  <?php if($field_havechild_keys[$field_have_child_last[$count_have_child_last]]){
                    array_push($field_child_values_isset,$field_havechild_keys[$field_have_child_last[$count_have_child_last]]);//入力があるフィールド名
                    array_push($values_isset_btn_txts,buy_btn_txt()[$count_have_child_last]);//リンク入力があるボタンのテキスト
                    }?> 
                <?php endfor;?>  
                <?php $buy_button_txt = acf_roop_display($filed_id_product)['field_child_keys'];//ボタンのテキスト=ラベル名
                $values_btn_count = -1;
                foreach($field_child_values_isset as $field_child_value_isset):
                  $values_btn_count++?>
                  <?php if($field_child_value_isset === reset($field_child_values_isset)){
                    echo '<ul class="buy__button--column">';
                  }?>
                    <li class="buy__button-link--column">
                      <a href="<?php echo $field_child_value_isset;?>" class="buy__link--border"><?php echo $values_isset_btn_txts[$values_btn_count];?></a>
                    </li>
                  <?php if($field_child_value_isset === end($field_child_values_isset)){
                    echo '</ul>';
                  }?>
                <?php endforeach;?>
                </div>
              </article>
            <?php 
            if(empty($next_field_check)){
            echo '</div></div></aside> <!-- end product -->';
            }?>
          <?php endif;?>
        <?php endfor;?>
      <?php endif;?>
      <?php coloring_share_btn(); ?>
      <?php 
      $filed_id_info = 3509;//カスタムフィールドグループのID
      $filed1_lists = acf_display($filed_id_info)['filed1_lists'];
      $isset_filed1_lists =array();
      foreach($filed1_lists as $field_label_name=>$field_name):?>
        <?php if(!empty(get_field($field_name))):
          $isset_filed1_lists[$field_label_name] = $field_name;?>  
        <?php endif?>
      <?php endforeach;?>    
      <?php foreach($isset_filed1_lists as $field_label_name => $isset_field_name):?>
          <?php if($isset_field_name === reset($isset_filed1_lists)){
            echo '<!--info--><aside class="content__aside"><h3 class="content__ttl3--punctuation">関連情報</h3><div class="content__aside-main--col">';
          }?>
          <dl class="info__list"><dt  class="info__list-ttl"><?php echo $field_label_name;?>&nbsp;&#058;&nbsp;</dt>
            <dd  class="info__list-item">
              <?php if($field_label_name === '価格'){
                echo '&yen;'.number_format(get_field($isset_field_name));
              }elseif($field_label_name === 'リンク'){
                  echo '<a href="'.get_field($isset_field_name).'" target="_blank" rel="”noopener" noreferrer”="" class="content__link">'.get_field($isset_field_name).'</a>';
              }else{
                echo get_field($isset_field_name);
              };?>
            </dd>
          </dl>
      <?php endforeach;?>
      <?php
      $field_root_names = acf_display($filed_id_info)['field_root_names'];
      $lastgroupfiled_names = acf_display($filed_id_info)['lastgroupfiled_names'];
      ?>
      <?php foreach($field_root_names as $field_root_name):?>
        <?php $isset_lastgroupfiled_names = array();?>
        <?php foreach($lastgroupfiled_names as $lastgroupfiled_name):?>
          <?php $lastfiled_name = acf_display($filed_id_info)['lastfiled_names'];?>
            <?php if(get_field($field_root_name)[$lastgroupfiled_name][$lastfiled_name[0]]):?>
              <?php $isset_lastgroupfiled_names[get_field($field_root_name)[$lastgroupfiled_name][$lastfiled_name[0]]] = get_field($field_root_name)[$lastgroupfiled_name][$lastfiled_name[1]];?>
            <?php endif;?>
        <?php endforeach;?>
      <?php endforeach;?>
      <?php foreach($isset_lastgroupfiled_names as $info_sorce => $info_sorce_link):?>
        <?php if($info_sorce_link === reset($isset_lastgroupfiled_names)):?>
          <?php  if(count($isset_filed1_lists) === 0){
            echo '<!--info--><aside class="content__aside"><h3 class="content__ttl3--punctuation">関連情報</h3><div class="content__aside-main--col">';
          }?>
          <dl class="info__list">
            <dt  class="info__list-ttl"><?php echo acf_display($filed_id_info)['lastgroupfiled_labels'];?>&nbsp;&#058;&nbsp;</dt>
            <dd  class="info__list-item">
        <?php endif;?>
              <a href="<?php echo $info_sorce_link;?>" target="_blank" rel=”noopener noreferrer” class="info__list-link"><?php echo $info_sorce;if($info_sorce_link !== end($isset_lastgroupfiled_names)){echo '&#44;';}?></a>
        <?php if($info_sorce_link === end($isset_lastgroupfiled_names)):?>
            </dd>
          </dl>
          </div></aside><!--end info-->
        <?php endif;?>   
      <?php endforeach;?>
      <?php if(count($isset_filed1_lists) !== 0 && count($isset_lastgroupfiled_names) === 0):?>
        </div></aside><!--end info-->
      <?php endif;?>   
      <?php  global $post;
      $postid=$post->ID;
      $postday=get_post_time( 'Y/m/d','', $postid,'');
      ?>
      <?php 
      $coution_filed_id = 3746;//カスタムフィールドグループのID
      $coution_lists = acf_display($coution_filed_id)['filed1_lists'];
      $isset_coution_filed_lists =array();
      foreach($coution_lists as $coution_field_name):?>
        <?php if(!empty(get_field($coution_field_name))):
            array_push($isset_coution_filed_lists,$coution_field_name);?>  
        <?php endif?>
      <?php endforeach;?>    
      <?php if(count($isset_coution_filed_lists) === 0):?>
        <div class="caution__list">
          <p class="caution__list-item">記載されている情報はすべて記事作成時<?php echo $postday;?>時点のもの</div>
        </ul>  
      <?php else:?>  
        <?php foreach($isset_coution_filed_lists as $isset_coution_field_name):?>
            <?php if($isset_coution_field_name === reset($isset_coution_filed_lists)){
              echo '<ul class="caution__list">';
              echo '<li class="caution__list-item">記載されている情報はすべて記事作成時'.$postday.'時点のもの</li>';
            }?>
              <li class="caution__list-item"><?php echo get_field($isset_coution_field_name);?></li>
        <?php endforeach;?>
        <?php if($isset_coution_field_name === end($isset_coution_filed_lists)){
          echo '</ul>';
        }?>
      <?php endif;?>  
      <div class="content__lead-bottom">
            <?php if($tags) : ?>
              <!-- tag-->
              <ul  class="content__lead-tag-list">
              <?php foreach($tags as $tag):?>
                <li class="content__lead-list-item"><a href="<?php echo get_tag_link($tag->term_id);?>" class="content__lead-list-taglink"><?php echo $tag->name;?></a></li><!-- end-tag-->
              <?php endforeach;?>
              </ul>
            <?php endif;?>
          </div>
      <?php if ( get_option('coloring_post_poster') != 'value2' &&get_field('writer__ttl')) :	?>
      <!-- writer -->
      <aside class="writer">
        <p class="writer__ttl">記事を書いた人からのひとこと</p>
        <div class="writer__content-outer">
          <div class="writer__imgArea">
            <?php
              $author = get_the_author_meta('ID');
              $author_img = get_avatar($author);
              $imgtag= '/<img.*?src=(["\'])(.+?)\1.*?>/i';
              if(preg_match($imgtag, $author_img, $imgurl)){
                $author_img = $imgurl[2];
              }
            ?>
            <img src="<?php echo $author_img; ?>" alt="<?php echo the_author_meta('display_name'); ?>" width="165" height="165" class="writer__img"/>
          </div>
          <div class="writer__contents">
          <p class="writer__description-ttl">ひとこと：<?php echo get_field('writer__ttl');?></p>
          <p class="writer__description"><?php echo get_field('writer__txt');?></p>
          </div>
        </div>
      </aside>
      <!-- end-writer -->
      <?php endif;?>
      <?php if (get_option('coloring_post_related') != 'value2' ) : ?>
      <!-- 関連記事 -->
      <?php
      // 総件数
      if(get_option('coloring_post_relatedNumber')){
        $max_post_num = get_option('coloring_post_relatedNumber');
      }else{
        $max_post_num = 5;
      }
      // 現在の記事にタグが設定されている場合
      $post_not_in = explode(',', get_option('coloring_post_not_in'));//除外記事
      array_push($post_not_in,$post -> ID,$post_not_in);
      if ( has_tag() ) {
        // 1.タグ関連の投稿を取得
        $tags = wp_get_post_tags($post->ID);
        $tag_ids = array();
        foreach($tags as $tag):
            array_push( $tag_ids, $tag -> term_id);
        endforeach ;
        $tag_args = array(
          'post__not_in' => $post_not_in ,
          'posts_per_page'=> $max_post_num,
          'tag__in' => $tag_ids,
          'orderby' => 'rand',
        );
        $rel_posts = get_posts($tag_args);
        // 総件数よりタグ関連の投稿が少ない場合は、カテゴリ関連の投稿からも取得する
        $rel_count = count($rel_posts);
        if ($max_post_num > $rel_count) {
          $categories = get_the_category($post->ID);
          $category_ID = array();
          foreach($categories as $category):
            array_push( $category_ID, $category -> cat_ID);
          endforeach ;
          // 取得件数は必要な数のみリクエスト
          $cat_args = array(
              'post__not_in' => $post_not_in ,
              'posts_per_page'=> ($max_post_num - $rel_count),
              'category__in' => $category_ID,
              'orderby' => 'rand',
          );
          $cat_posts = get_posts($cat_args);
          $rel_posts = array_merge($rel_posts, $cat_posts);
        }
      }else{ // 現在の記事にタグが設定されていない場合
          echo $rel_count;
          $categories = get_the_category($post->ID);
          $category_ID = array();
          foreach($categories as $category):
              array_push( $category_ID, $category -> cat_ID);
          endforeach ;
          // 取得件数は必要な数のみリクエスト
          $cat_args = array(
              'post__not_in' => $post_not_in ,
              'posts_per_page'=> ($max_post_num),
              'category__in' => $category_ID,
              'orderby' => 'rand',
          );
          $cat_posts = get_posts($cat_args);
          $rel_posts = $cat_posts;
      }
      ?>
      <aside class="content__aside">
        <h3 class="content__ttl3--punctuation" id="related">この記事を読んだ方にオススメ</h3>
        <?php
        // 1件以上
        if( count($rel_posts) > 0 ):
          ?>
          <ul class="content__aside-main--col">
          <?php 
          foreach ($rel_posts as $post):
            setup_postdata($post);
            // thumbnailサイズの画像内容を取得
            $thumbnail_id = get_post_thumbnail_id(); 
            $thumb_img = wp_get_attachment_image_src( $thumbnail_id , 'middle' );
            // サムネイル画像出力
            $thumb_src = $thumb_img[0];
            $thumb_width = $thumb_img[1];
            $thumb_height = $thumb_img[2];
            ?>
            <li class="archive__item-flex--min">
              <a class="archive__thumbnail-left" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <?php if(has_post_thumbnail()) : ?>
                  <img src="<?php echo $thumb_src; ?>" alt="<?php the_title(); ?>" width="<?php echo $thumb_width; ?>" height="<?php echo $thumb_height; ?>" class="archive__img"/>
                <?php else:?>
                  <img src="<?php echo get_template_directory_uri(); ?>/img/archive/thumnail_min.jpg" alt="NO IMAGE" width="313.96" height="165.56" class="related__img"/>  
                <?php endif;?>
              </a>
              <div class="archive__lead-right">
              <div class="archive__ttl-col-center">
                <h3 class="archive__ttl--min">
                  <a href="<?php the_permalink(); ?>" class="archive__ttl-link">
                    <?php the_title(); ?>
                  </a>
                </h3>
                </div> 
                <a class="archive__link-vector">
                  <img src="<?php echo get_template_directory_uri() ?>/img/button/link_icon--middle.png" width="19px" height="8px" class="link__icon">
                </a>
              </div>
          <?php
          endforeach;?>
            </li>
          </ul>
        <?php else:?>  
          <p class="related__not-contents">関連記事はありませんでした</p>
        <?php  endif;?>
      </aside>
      <?php wp_reset_postdata();?>
      <!-- end-関連記事 -->
      <?php endif;?>
  </main>
  <!-- l-sidebar -->
  <?php get_sidebar(); ?>
  <!-- end sidebar -->
</div><!--end wrapper-->
<?php get_footer(); ?> 