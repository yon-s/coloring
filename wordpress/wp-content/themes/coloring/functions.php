<?php
//サムネイル設定ができるように
add_theme_support('post-thumbnails');
//WordPressのバージョン表示させない
remove_action('wp_head','wp_generator');
//img size
  //記事リスト
  add_image_size('post-list', 864, 455.63);
  add_image_size('post-list-middle', 576, 303.75);
  add_image_size('post-list-min', 337, 177.71);
  //記事
  add_image_size('post', 2058, 1085.27);
  add_image_size('post-middle', 1372, 723.52);
  add_image_size('post-min', 804, 423.98);
  //商品
  add_image_size('product', 456, 624);
//Original sanitize_callback
  // CheckBox
  function coloring_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true == $checked ) ? true : false );
  }
  // radio/select
  function coloring_sanitize_select( $input, $setting ) {
    $input = sanitize_key( $input );
      $choices = $setting->manager->get_control($setting->id)->choices;
      return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
  }
  // number limit
  function coloring_sanitize_number_range( $number, $setting ) {
    $number = absint( $number );//整数
    $atts = $setting->manager->get_control( $setting->id )->input_attrs;
    $min = ( isset( $atts['min'] ) ? $atts['min'] : $number );
    $max = ( isset( $atts['max'] ) ? $atts['max'] : $number );
    $step = ( isset( $atts['step'] ) ? $atts['step'] : 1 );
    return ( $min <= $number && $number <= $max && is_int( $number / $step ) ? $number : $setting->default );
  }
  // uploader
  function coloring_sanitize_image( $image, $setting ) {
    $mimes = array(
      'jpg|jpeg|jpe' => 'image/jpeg',
      'gif'          => 'image/gif',
      'png'          => 'image/png',
      'bmp'          => 'image/bmp',
      'tif|tiff'     => 'image/tiff',
      'ico'          => 'image/x-icon'
    );
    $file = wp_check_filetype( $image, $mimes );
    return ( $file['ext'] ? $image : $setting->default );
  }
//外観>カスタマイズに項目追加
//SNS・OGP設定画面
function coloring_social_cutomizer( $wp_customize ) {
  // セクション
  $wp_customize->add_section( 'coloring_social_section', array(
    'title'     => 'SNS・OGP設定',
    'priority'  => 1,
  ));
  //OGP画像 セッティング
  $wp_customize->add_setting('coloring_social_image_ogp', array(
    'type' => 'theme_mod',
    'transport' => 'postMessage',
    'sanitize_callback' => 'coloring_sanitize_image',
  ));
  //OGP画像 コントロール
  $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'coloring_social_image_ogp', array(
    'section' => 'coloring_social_section',
    'settings' => 'coloring_social_image_ogp',
    'label' => '■[OGP]画像の設定',
    'description' => '投稿にアイキャッチ画像が登録されていない時に表示する画像<br>
（縦600 × 横1200px以上の画像を登録してください）',
  )));
  // TwitterCard セッティング
  $wp_customize->add_setting( 'coloring_social_TwitterCard', array(
    'default'   => 'summary',
    'type' => 'option',
    'transport' => 'postMessage',
    'sanitize_callback' => 'coloring_sanitize_select',
  ));
  // TwitterCard コントロール
  $wp_customize->add_control( 'coloring_social_TwitterCard', array(
    'section'   => 'coloring_social_section',
    'settings'  => 'coloring_social_TwitterCard',
    'label'     => '■[OGP]Twitter Cardの種類を選択',
    'description' => 'Twitterで記事がシェアされた時のカードデザインを選択',
    'type'      => 'select',
    'choices'   => array(
        'summary' => 'Summaryカード(default)',
        'summary_large_image' => 'Summary with Large Imageカード',
    ),
  ));
  // FacebookAPPID セッティング
  $wp_customize->add_setting( 'coloring_social_FBAppId', array(
    'type' => 'option',
    'transport' => 'postMessage',
    'sanitize_callback' => 'wp_filter_nohtml_kses',
  ));
  // FacebookAPPID コントロール
  $wp_customize->add_control( 'coloring_social_FBAppId', array(
    'section'   => 'coloring_social_section',
    'settings'  => 'coloring_social_FBAppId',
    'label'     => '■[OGP]FacebookのAPPID',
    'description' => 'FacebookのApp IDを記入',
    'type'      => 'text',
  ));
}
add_action( 'customize_register', 'coloring_social_cutomizer' );
//セットした画像のURLを取得
function get_coloring_image_ogp() { return esc_url(get_theme_mod('coloring_social_image_ogp'));}
//SEO設定画面
function coloring_seo_cutomizer( $wp_customize ) {
  $coloring_seo_section_desc = '';
  $wp_customize->add_section( 'coloring_seo_section', array(
		'title'     => 'SEO設定',
		'priority'  => 1,
		'description' => $coloring_seo_section_desc,
	));
  if ( get_option( 'show_on_front' ) != 'page' ) {//TOPページ固定(page.php)以外
    // TOPページ
      //<title> 
        //セッティング
        $wp_customize->add_setting( 'coloring_seo_titleTop', array(
          'default'   => get_bloginfo( 'description' ) .coloring_title_separator() .get_bloginfo( 'name' ),
          'type' => 'option',
          'transport' => 'postMessage',//即時反映
          'sanitize_callback' => 'sanitize_text_field',//サニタイズ
        ));
        //コントロール
        $wp_customize->add_control(
          'coloring_seo_titleTop', array(
            'section'   => 'coloring_seo_section',
            'settings'  => 'coloring_seo_titleTop',
            'label' => '■TOPページの&lt;title&gt;',
            'description' => 'TOPページの&lt;title&gt;を入力<br>(未入力の場合は「設定」→「一般」の【キャッチフレーズ │ サイトのタイトル】が表示されます)',
            'type'      => 'text',
        ));
        //サイト名
          //セッティング
          $wp_customize->add_setting( 
            'coloring_seo_titleTopName', array(
              'type' => 'option',
              'sanitize_callback' => 'coloring_sanitize_checkbox',
          ));
          //コントロール
          $wp_customize->add_control( 'coloring_seo_titleTopName', array(
            'section'   => 'coloring_seo_section',
            'settings'  => 'coloring_seo_titleTopName',
            'label'     => '「'.coloring_title_separator().' '.get_bloginfo( 'name' ).'」を表示する',
            'type'      => 'checkbox',
          ));
        //<meta description>
          //セッティング
          $wp_customize->add_setting('coloring_seo_descriptionTop', array(
            'default'   => get_bloginfo( 'description' ),
            'type' => 'option',
            'transport' => 'postMessage',
		        'sanitize_callback' => 'sanitize_text_field',
          ));
          //コントロール
          $wp_customize->add_control( 
            'coloring_seo_descriptionTop', array(
            'section'   => 'coloring_seo_section',
            'settings'  => 'coloring_seo_descriptionTop',
            'label' => '■TOPページの&lt;meta description&gt;',
            'description' => 'TOPページの&lt;meta  description&gt;を入力',
            'type'      => 'textarea',
          )); 
  }
        // CSS非同期読み込み 
          //セッティング
          $wp_customize->add_setting( 
            'coloring_seo_cssLoad', array(
              'default'   => 'value1',
              'type' => 'option',
              'transport' => 'postMessage',
              'sanitize_callback' => 'coloring_sanitize_select',
            ));
          //コントロール
          $wp_customize->add_setting(
            'coloring_seo_cssLoad', array(
              'section' => 'coloring_seo_section',
              'setting' => 'coloring_seo_cssLoad',
              'label' => '■CSS非同期読込設定',
              'description' => 'CSSの非同期読み込みを有効化するか選択<br>
		          （CSS非同期読み込みを有効化するとページの読み込み速度が向上する代わりに、一瞬デザインが崩れて見えることがあります。※有効にするとfooterに一行JavaScript記述）<br>
		          <br>
		          ※無効にする場合は下記のチェック項目をすべてOFFにしてください。',
              'type'      => 'select',
              'choices'   => array(
                'value1' => '無効(default)',
                'value2' => '有効',
              ),
          ));
        //メインCSS
          //セッティング
          $wp_customize->add_setting(
            'coloring_seo_cssLoad-main', array(
              'type' => 'option',
              'transport' => 'postMessage',
              'sanitize_callback' => 'coloring_sanitize_checkbox',
            )
          );
          //コントロール
          $wp_customize->add_control(
            'coloring_seo_cssLoad-main',array(
              'section'   => 'coloring_seo_section',
              'settings'  => 'coloring_seo_cssLoad-main',
              'label'     => 'メインCSS(style.css)を非同期読み込みする',
		          'type'      => 'checkbox',
            )
          );
        // AdobeフォントCSS 
          //セッティング
          $wp_customize->add_setting( 'coloring_seo_cssLoad-adobe', array(
            'type' => 'option',
            'transport' => 'postMessage',
            'sanitize_callback' => 'coloring_sanitize_checkbox',
          ));
          //コントロール
          $wp_customize->add_control( 'coloring_seo_cssLoad-adobe', array(
            'section'   => 'coloring_seo_section',
            'settings'  => 'coloring_seo_cssLoad-lato',
            'label'     => 'AdobeフォントCSSを非同期読み込みする',
            'type'      => 'checkbox',
          ));
}
add_action( 'customize_register', 'coloring_seo_cutomizer' );
//公式SNSアカウントのリスト
function sns_lists(){
  $sns_lists = array(
    'twitter' => array(
      'title' => 'Twitter', //ラベルに表示する名前
      'url' => 'https://twitter.com/',//URL
      'font' => 'twitter'//font-awsomeの fa-(この部分)
    ),
    'facebook' => array(
      'title' => 'Facebook',
      'url' => 'https://ja-jp.facebook.com/',
      'font' => 'facebook-f'
    ),
    'instagram' => array(
      'title' => 'Instagram',
      'url' => 'https://www.instagram.com/',
      'font' => 'instagram'
    ),
    'line' => array(
      'title' => 'LINE',
      'url' => 'https://page.line.me/',
      'font' => 'line'
    ),
    'spotify' => array(
      'title' => 'spotify',
      'url' => 'https://open.spotify.com/user/',
      'font' => 'spotify'
    )
  );
  return $sns_lists;
}
//基本設定画面
function coloring_sns__cutomizer( $wp_customize ) {
  $wp_customize->add_section( 'coloring_sns_section', array(
		'title'     => 'サイトのSNS表示設定',
		'priority'  => 1,
    'description' =>  'サイトのSNS表示設定',
	));
  //SNSアイコン
    foreach(sns_lists() as $sns_name => $sns_list){
      //セッティング
      $wp_customize->add_setting(
        'coloring_sns_'.$sns_name,array(
          'type' => 'option',
		      'transport' => 'postMessage',
		      'sanitize_callback' => 'sanitize_text_field',
        )
      );
      //コントロール
      $wp_customize->add_control(
        'coloring_sns_'.$sns_name,array(
          'section'   => 'coloring_sns_section',
          'settings'  => 'coloring_sns_'.$sns_name,
          'label' => $sns_list['title'].'のアカウント名',
          'description' => $sns_list['title'].'のアカウントIDを入力※@以降',
          'type'      => 'text',
        )
      );
    }
}
add_action( 'customize_register', 'coloring_sns__cutomizer' );
// すべての固定ページ情報の取得
function page_list(){
  $page_list = get_posts( 'numberposts=-1&order=ASC&post_type=page' ); 
  return $page_list;
} 
function cat_lists(){
  //カテゴリー一覧
  $catParent = array(
    'parent' => 0,
    'orderby' => 'trem_order',
    'order' => 'DESC',
  );
  $categorys = get_categories( $catParent );
  return $categorys;
}
//headerカスタマイズ
function coloring_header_cutomizer( $wp_customize ) {
  $wp_customize->add_section( 'coloring_header_section', array(
		'title'     => 'headerの表示設定',
		'priority'  => 1,
    'description' =>  'headerの表示設定',
	));
  //カテゴリーリスト
    for($cattype=0; $cattype <= 1; $cattype++){
      if($cattype === 0){
        $cat_list_class = 'global_navi';
      }else{
        $cat_list_class = 'side_bar';
      }
        //セッティング
        $wp_customize->add_setting(
          'coloring_'.$cat_list_class.'_cat',array(
            'type' => 'option',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
          )
        );
        //コントロール
        $wp_customize->add_control(
          'coloring_'.$cat_list_class.'_cat',array(
            'title'     => 'カテゴリ',
            'section'   => 'coloring_header_section',
            'settings'  => 'coloring_'.$cat_list_class.'_cat',
            'label' => $cat_list_class.'部分のカテゴリーリストクラス名',
            'description' => 'side_barならliのclass(この部分)__list-item',
            'type'      => 'text',
          )
        );
      foreach(cat_lists() as $category){
        //セッティング
        $wp_customize->add_setting( 'coloring_'.get_option('coloring_'.$cat_list_class.'_cat').'_cat_'.$category->cat_ID, array(
          'type' => 'option',
          'transport' => 'postMessage',
          'sanitize_callback' => 'coloring_sanitize_checkbox',
        ));
        //コントロール
        $wp_customize->add_control( 'coloring_'.get_option('coloring_'.$cat_list_class.'_cat').'_cat_'.$category->cat_ID, array(
          'section'   => 'coloring_header_section',
          'settings'  => 'coloring_'.get_option('coloring_'.$cat_list_class.'_cat').'_cat_'.$category->cat_ID,
          'label'     => '['.$category->name.']を'.$cat_list_class.'に表示しない。',
          'type'      => 'checkbox',
        ));
      }
    }
  //固定ページリンク
    //セッティング
      $wp_customize->add_setting(
        'coloring_header_class',array(
          'type' => 'option',
          'transport' => 'postMessage',
          'sanitize_callback' => 'sanitize_text_field',
        )
      );
      //コントロール
      $wp_customize->add_control(
        'coloring_header_class',array(
          'title'     => '固定ページ',
          'section'   => 'coloring_header_section',
          'settings'  => 'coloring_header_class',
          'label' => '固定ページのクラス名',
          'description' => 'liのclass(この部分)__list-item',
          'type'      => 'text',
        )
      );  
  foreach ( page_list() as $page_item ) {
    //セッティング
    $wp_customize->add_setting( 'coloring_'.get_option('coloring_header_class').'-link-id'.get_the_title($page_item->ID), array(
      'type' => 'option',
      'transport' => 'postMessage',
      'sanitize_callback' => 'coloring_sanitize_checkbox',
    ));
    //コントロール
    $wp_customize->add_control( 'coloring_'.get_option('coloring_header_class').'-link-id'.get_the_title($page_item->ID), array(
      'section'   => 'coloring_header_section',
      'settings'  => 'coloring_'.get_option('coloring_header_class').'-link-id'.get_the_title($page_item->ID),
      'label'     => '['.get_the_title($page_item->ID).']を'.get_option('coloring_header_class').'のリンクに表示しない。',
      'type'      => 'checkbox',
    ));
  }
}
add_action( 'customize_register', 'coloring_header_cutomizer' );
//loopカスタマイズ
  function coloring_loop_cutomizer( $wp_customize ) {
    $wp_customize->add_section( 'coloring_loop_section', array(
      'title'     => '全記事一覧の表示設定',
      'priority'  => 1,
      'description' =>  '全記事一覧の表示設定',
    ));
    foreach(cat_lists() as $category){
      //セッティング
      $wp_customize->add_setting( 'coloring_loop_'.$category->cat_ID, array(
        'type' => 'option',
        'transport' => 'postMessage',
        'sanitize_callback' => 'coloring_sanitize_checkbox',
      ));
      //コントロール
      $wp_customize->add_control( 'coloring_loop_'.$category->cat_ID, array(
        'section'   => 'coloring_loop_section',
        'settings'  => 'coloring_loop_'.$category->cat_ID,
        'label'     => '['.$category->name.']を記事一覧に表示しない。',
        'type'      => 'checkbox',
      ));
    }
  }
  add_action( 'customize_register', 'coloring_loop_cutomizer' );
//footerカスタマイズ
  function coloring_footer_cutomizer( $wp_customize ) {
    $wp_customize->add_section( 'coloring_footer_section', array(
      'title'     => 'footerの表示設定',
      'priority'  => 1,
      'description' =>  'footerの表示設定',
    ));
  //固定ページリンク
    //セッティング
    $wp_customize->add_setting(
      'coloring_footer_class',array(
        'type' => 'option',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
      )
    );
    //コントロール
    $wp_customize->add_control(
      'coloring_footer_class',array(
        'title'     => '固定ページ',
        'section'   => 'coloring_footer_section',
        'settings'  => 'coloring_footer_class',
        'label' => '固定ページのクラス名',
        'description' => '固定ページのクラスcoloring_footer_(この部分)-link-idの名前',
        'type'      => 'text',
      )
    );
    foreach ( page_list() as $page_item ) {
      //セッティング
      $wp_customize->add_setting( 'coloring_'.get_option('coloring_footer_class').'-link-id'.get_the_title($page_item->ID), array(
        'type' => 'option',
        'transport' => 'postMessage',
        'sanitize_callback' => 'coloring_sanitize_checkbox',
      ));
      //コントロール
      $wp_customize->add_control( 'coloring_'.get_option('coloring_footer_class').'-link-id'.get_the_title($page_item->ID), array(
        'section'   => 'coloring_footer_section',
        'settings'  => 'coloring_'.get_option('coloring_footer_class').'-link-id'.get_the_title($page_item->ID),
        'label'     => '['.get_the_title($page_item->ID).']を'.get_option('coloring_footer_class').'のリンクに表示しない。',
        'type'      => 'checkbox',
      ));
    }
     //フッターに表示する注意事項
      //セッティング
      $wp_customize->add_setting( 'coloring_footer-caution', array(
        'type' => 'option',
        'sanitize_callback' => 'sanitize_text_field',
      ));
      //コントロール
      $wp_customize->add_control( 'coloring_footer-caution', array(
        'section'   => 'coloring_footer_section',
        'settings'  => 'coloring_footer-caution',
        'description' => 'フッターに表示する注意事項を入力',
        'type'      => 'textarea',
      ));    
  }
  add_action( 'customize_register', 'coloring_footer_cutomizer' );
//投稿ページ(single.php)各種設定画面
  //記事シェア用のSNSボタン
  function sns_share_lists(){
    $sns_share_lists = array(
      'Facebook' => array(
        'options' => 'facebook', //セッティング名
        'url' => 'http://www.facebook.com/sharer.php?u='. urlencode(get_permalink()) .'&amp;t='. urlencode(the_title("","",0)),//シェア用URL
        'font' => 'facebook-f'//font-awsomeの fa-(この部分)
      ),
      'Twitter' => array(
        'options' => 'twitter', //セッティング名
        'url' => 'http://twitter.com/intent/tweet?text='. urlencode(the_title("","",0)) .'&amp;'. urlencode(get_permalink()) .'&amp;url='. urlencode(get_permalink()),//シェア用URL
        'font' => 'twitter'//font-awsomeの fa-(この部分)
      ),
      'Google+' => array(
        'options' => 'google', //セッティング名
        'url' => 'https://plus.google.com/share?url='. urlencode(get_permalink()),//シェア用URL
        'font' => 'google-plus-g'//font-awsomeの fa-(この部分)
      ),
      'はてなブックマーク' => array(
        'options' => 'hatebu', //セッティング名
        'url' => 'http://b.hatena.ne.jp/add?mode=confirm&amp;url='. urlencode(get_permalink()) .'&amp;title='. urlencode(the_title("","",0)),//シェア用URL
        'font' => 'hatebu'//font-awsomeの fa-(この部分)
      ),
      'pocket' => array(
        'options' => 'pocket', //セッティング名
        'url' => 'http://getpocket.com/edit?url='. urlencode(get_permalink()),//シェア用URL
        'font' => 'get-pocket'//font-awsomeの fa-(この部分)
      ),
      'LINE' => array(
        'options' => 'line', //セッティング名
        'url' => 'http://line.naver.jp/R/msg/text/?'. urlencode(the_title("","",0)) .'%0D%0A'. urlencode(get_permalink()),//シェア用URL
        'font' => 'line'//font-awsomeの fa-(この部分)
      ),
      'シェア' => array(
        'options' => 'share', //セッティング名
        'url' => 'javascript:void(0)',//シェア用URL
        'font' => 'share-alt'//font-awsomeの fa-(この部分)
      ),
    );
    return $sns_share_lists;
  }
  function coloring_post_cutomizer( $wp_customize ) {
    //セクション
    $wp_customize->add_section( 'coloring_post_section', array(
      'title'     => '投稿ページ設定',
      'priority'  => 1,
    ));
    // 目次
      //表示/非表示 
        //セッティング
        $wp_customize->add_setting( 'coloring_post_outline', array(
          'default'   => 'value1',
          'type' => 'option',
          'sanitize_callback' => 'coloring_sanitize_select',
        ));
        //コントロール
        $wp_customize->add_control( 'coloring_post_outline', array(
          'section'   => 'coloring_post_section',
          'settings'  => 'coloring_post_outline',
          'label'     => '■目次の表示/非表示',
          'description' => '投稿ページに目次を表示するか選択<br>
          (記事内の最初のhタグの手前に自動で挿入されます。※[outline]ショートコードで好きな位置に表示可能)',
          'type'      => 'select',
          'choices'   => array(
            'value1' => '表示する(default)',
            'value2' => '表示しない',
          ),
        ));
      //表示するための最小見出し数
        //セッティング
        $wp_customize->add_setting( 'coloring_post_outline_number', array(
          'default'   => '1',
          'type' => 'option',
          'sanitize_callback' => 'coloring_sanitize_number_range',
        ));
        //コントロール
        $wp_customize->add_control( 'coloring_post_outline_number', array(
          'section'   => 'coloring_post_section',
          'settings'  => 'coloring_post_outline_number',
          'description' => '目次を表示するための最小見出し数を指定',
          'type'      => 'number',
          'input_attrs' => array(
                'step'     => '1',
                'min'      => '1',
                'max'      => '50',
            ),
        ));
      //パネルデフォルト設定
        //セッティング
        $wp_customize->add_setting('coloring_post_outline_close', array( 
          'type' => 'option',
          'sanitize_callback' => 'coloring_sanitize_checkbox',
          ));
        //コントロール
        $wp_customize->add_control('coloring_post_outline_close', array( 
          'section' => 'coloring_post_section', 
          'settings' => 'coloring_post_outline_close', 
          'label'     => '目次パネルをデフォルトで閉じておく',
          'type'      => 'checkbox',
        ));
      // 関連記事  
        //表示/非表示
          //セッティング
          $wp_customize->add_setting( 'coloring_post_related', array(
            'default'   => 'value1',
            'type' => 'option',
            'sanitize_callback' => 'coloring_sanitize_select',
          ));
          //コントロール
          $wp_customize->add_control( 'coloring_post_related', array(
            'section'   => 'coloring_post_section',
            'settings'  => 'coloring_post_related',
            'label'     => '■関連記事の表示/非表示',
            'description' => '投稿ページに関連記事を表示するか選択',
            'type'      => 'select',
            'choices'   => array(
              'value1' => '表示する(default)',
              'value2' => '表示しない',
            ),
          ));
        //表示最大数
          //セッティング
          $wp_customize->add_setting(  'coloring_post_relatedNumber', array(
            'default'   => '3',
            'type' => 'option',
            'sanitize_callback' => 'coloring_sanitize_number_range',
          ));
          //コントロール
          $wp_customize->add_control( 'coloring_post_relatedNumber', array(
            'section'   => 'coloring_post_section',
            'settings'  => 'coloring_post_relatedNumber',
            'description' => '関連記事を表示する時の最大数を指定',
            'type'      => 'number',
            'input_attrs' => array(
                  'step'     => '1',
                  'min'      => '1',
                  'max'      => '10',
              ),
          ));
        //関連記事に表示しない記事
        //セッティング
        $wp_customize->add_setting(
          'coloring_post_not_in',array(
            'type' => 'option',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
          )
        );
        //コントロール
        $wp_customize->add_control(
          'coloring_post_not_in',array(
            'section'   => 'coloring_post_section',
            'settings'  => 'coloring_post_not_in',
            'description' => '関連記事で表示しない投稿のIDを入力(複数ある場合はコンマ区切り)',
            'type'      => 'text',
          )
        );   
      //購入先リンクボタン
        //ボタンの数  
          //セッティング
          $wp_customize->add_setting( 'coloring_buy_btn_count', array(
            'default'   => '4',
            'type' => 'option',
            'sanitize_callback' => 'coloring_sanitize_number_range',
          ));
          //コントロール
          $wp_customize->add_control( 'coloring_buy_btn_count', array(
            'section'   => 'coloring_post_section',
            'settings'  => 'coloring_buy_btn_count',
            'label' => '紹介商品購入先リンクボタン',
            'description' => '■紹介商品購入先リンクボタンの数',
            'type'      => 'number',
            'input_attrs' => array(
                  'step'     => '1',
                  'min'      => '1',
                  'max'      => '50',
              ),
          ));
      for($buy_link_txt_count = 1; $buy_link_txt_count <= (get_option('coloring_buy_btn_count')); $buy_link_txt_count++){
        //セッティング
        $wp_customize->add_setting(
          'coloring_buy_link_txt'.$buy_link_txt_count,array(
            'type' => 'option',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
          )
        );
        //コントロール
        $wp_customize->add_control(
          'coloring_buy_link_txt'.$buy_link_txt_count,array(
            'section'   => 'coloring_post_section',
            'settings'  => 'coloring_buy_link_txt'.$buy_link_txt_count,
            'description' => '紹介商品の購入先リンク'.$buy_link_txt_count.'つめのボタンのテキスト',
            'type'      => 'text',
          )
        );    
      }
      // 投稿者情報の表示/非表示  
        //セッティング
        $wp_customize->add_setting( 'coloring_post_poster', array(
          'default'   => 'value1',
          'type' => 'option',
          'sanitize_callback' => 'coloring_sanitize_select',
        ));
        //コントロール
        $wp_customize->add_control( 'coloring_post_poster', array(
          'section'   => 'coloring_post_section',
          'settings'  => 'coloring_post_poster',
          'label'     => '■投稿者情報の表示/非表示',
          'description' => '投稿ページに投稿者情報を表示するか選択',
          'type'      => 'select',
          'choices'   => array(
            'value1' => '表示する(default)',
            'value2' => '表示しない',
          ),
        ));
      //記事のシェアボタン
        //表示/非表示 
          //セッティング
          $wp_customize->add_setting( 'coloring_post_shareBottom', array(
            'default'   => 'value1',
            'type' => 'option',
            'sanitize_callback' => 'coloring_sanitize_select',
          ));
          //コントロール
          $wp_customize->add_control( 'coloring_post_shareBottom', array(
            'section'   => 'coloring_post_section',
            'settings'  => 'coloring_post_shareBottom',
            'description' => '投稿ページの下部にシェアボタンを表示するか選択',
            'type'      => 'select',
            'choices'   => array(
              'value1' => '表示する(default)',
              'value2' => '表示しない',
            ),
          ));
        //facebook
        foreach (sns_share_lists() as  $sns_share_ttl => $sns_share_date) {
          //セッティング
          $wp_customize->add_setting('coloring_post_share['.$sns_share_date['options'].']', array( 
            'type' => 'option',
            'sanitize_callback' => 'coloring_sanitize_checkbox',
          ));
          //コントロール
          $wp_customize->add_control('coloring_post_share_'.$sns_share_date['options'], array( 
            'section' => 'coloring_post_section', 
            'settings' => 'coloring_post_share['.$sns_share_date['options'].']', 
            'label'     => $sns_share_ttl.'ボタンを表示する',
            'type'      => 'checkbox',
          ));
        }
        //このサイトで人気の記事
          //セッティング
          $wp_customize->add_setting(
            'coloring_pickup_link_url',array(
              'type' => 'option',
              'transport' => 'postMessage',
              'sanitize_callback' => 'sanitize_text_field',
            )
          );
          //コントロール
          $wp_customize->add_control(
            'coloring_pickup_link_url',array(
              'section'   => 'coloring_post_section',
              'settings'  => 'coloring_pickup_link_url',
              'label' => '■記事ページに表示させたい記事',
              'description' => '表示する記事のスラッグ',
              'type'      => 'text',
            )
          );
  }
  add_action( 'customize_register', 'coloring_post_cutomizer' );
//Aboutページ各種設定画面
  function concept_counter(){
    return 3;
  };//コンセプトの入力項目数
  function coloring_about_cutomizer( $wp_customize ) {
    //セクション
    $wp_customize->add_section( 'coloring_about_section', array(
      'title'     => 'Aboutページ設定',
      'priority'  => 1,
    ));
      //aboutページのスラッグ
        //セッティング
        $wp_customize->add_setting(
          'coloring_about_slug',array(
            'type' => 'option',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
          )
        );
        //コントロール
        $wp_customize->add_control(
          'coloring_about_slug',array(
            'section'   => 'coloring_about_section',
            'settings'  => 'coloring_about_slug',
            'description' => 'Aboutページのスラッグを入力<br>'.get_bloginfo('home').'/(この部分)',
            'type'      => 'text',
          )
        );
    for($concept_no = 1; $concept_no <= concept_counter(); $concept_no++){
      //コンセプトイメージ
        //セッティング
        $wp_customize->add_setting(
          'coloring_about_concept_img'.$concept_no,array(
            'type' => 'theme_mod',
            'sanitize_callback' => 'coloring_sanitize_image',
          )
        );
        //コントロール
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize,
          'coloring_about_concept_img'.$concept_no,array(
            'section'   => 'coloring_about_section',
            'settings'  => 'coloring_about_concept_img'.$concept_no,
            'label' => 'concept'.$concept_no,
            'description' => 'concept'.$concept_no.'の画像を指定してください。(縦130 × 160pxの透過PING画像)',
          )
        )
        );
      //コンセプトタイトル
        //セッティング
        $wp_customize->add_setting(
          'coloring_about_concept_ttl'.$concept_no,array(
            'type' => 'option',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
          )
        );
        //コントロール
        $wp_customize->add_control(
          'coloring_about_concept_ttl'.$concept_no,array(
            'section'   => 'coloring_about_section',
            'settings'  => 'coloring_about_concept_ttl'.$concept_no,
            'description' => 'concept'.$concept_no.'のタイトルを入力してください',
            'type'      => 'text',
          )
        );
      //コンセプト説明文
        //セッティング
        $wp_customize->add_setting(
          'coloring_about_concept_desc'.$concept_no,array(
            'type' => 'option',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
          )
        );
        //コントロール
        $wp_customize->add_control(
          'coloring_about_concept_desc'.$concept_no,array(
            'section'   => 'coloring_about_section',
            'settings'  => 'coloring_about_concept_desc'.$concept_no,
            'description' => 'concept'.$concept_no.'の説明文を入力してください',
            'type'      => 'textarea',
          )
        );
    }//endfor 
    //aboutページの説明文
      //セッティング
      $wp_customize->add_setting(
        'coloring_about_lasttext',array(
          'type' => 'option',
          'transport' => 'postMessage',
          'sanitize_callback' => 'sanitize_text_field',
        )
      );
      //コントロール
      $wp_customize->add_control(
        'coloring_about_lasttext',array(
          'section'   => 'coloring_about_section',
          'settings'  => 'coloring_about_lasttext',
          'description' => 'Aboutページのスラッグを入力<br>'.get_bloginfo('home').'/(この部分)',
          'type'      => 'textarea',
        )
      );
  }
  add_action( 'customize_register', 'coloring_about_cutomizer' );
//お問い合わせページ各種設定画面
function caution_conter(){
  return 3;//お問い合わせページの注意事項文章入力項目数
}
function coloring_contact_cutomizer( $wp_customize ) {
  //セクション
  $wp_customize->add_section( 'coloring_contact_section', array(
    'title'     => 'お問い合わせページ設定',
    'priority'  => 1,
  ));
    //お問い合わせページ
      //スラッグ
        //セッティング
        $wp_customize->add_setting(
          'coloring_contact_slug',array(
            'type' => 'option',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
          )
        );
        //コントロール
        $wp_customize->add_control(
          'coloring_contact_slug',array(
            'section'   => 'coloring_contact_section',
            'settings'  => 'coloring_contact_slug',
            'label' =>'お問い合わせページ',
            'description' => 'お問い合わせページのスラッグを入力<br>'.get_bloginfo('home').'/(この部分)',
            'type'      => 'text',
          )
        );
      //注意事項
      for($caution_no = 1; $caution_no <= caution_conter(); $caution_no++){
        //セッティング
        $wp_customize->add_setting(
          'coloring_contact_caution'.$caution_no,array(
            'type' => 'option',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
          )
        );
        //コントロール
        $wp_customize->add_control(
          'coloring_contact_caution'.$caution_no,array(
            'section'   => 'coloring_contact_section',
            'settings'  => 'coloring_contact_caution'.$caution_no,
            'description' => 'お問い合わせページの注意事項文章'.$caution_no,
            'type'      => 'textarea',
          )
        );
      }
    //完了画面
      //タイトル
        //セッティング
        $wp_customize->add_setting(
          'coloring_contact_sentttl',array(
            'type' => 'option',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
          )
        );
        //コントロール
        $wp_customize->add_control(
          'coloring_contact_sentttl',array(
            'section'   => 'coloring_contact_section',
            'settings'  => 'coloring_contact_sentttl',
            'label' =>'お問い合わせ完了ページ',
            'description' => 'お問い合わせ完了ページのタイトル',
            'type'      => 'text',
          )
        );
      //文章
        //セッティング
        $wp_customize->add_setting(
          'coloring_contact_senttxt',array(
            'type' => 'option',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
          )
        );
        //コントロール
        $wp_customize->add_control(
          'coloring_contact_senttxt',array(
            'section'   => 'coloring_contact_section',
            'settings'  => 'coloring_contact_senttxt',
            'description' => 'お問い合わせ完了ページの文章',
            'type'      => 'textarea',
          )
        );
      //注意事項  
      for($caution_no = 1; $caution_no <= caution_conter(); $caution_no++){
        //セッティング
        $wp_customize->add_setting(
          'coloring_contact_sentcaution'.$caution_no,array(
            'type' => 'option',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
          )
        );
        //コントロール
        $wp_customize->add_control(
          'coloring_contact_sentcaution'.$caution_no,array(
            'section'   => 'coloring_contact_section',
            'settings'  => 'coloring_contact_sentcaution'.$caution_no,
            'description' => 'お問い合わせ完了ページの注意事項文章'.$caution_no,
            'type'      => 'textarea',
          )
        );
      }
}
add_action( 'customize_register', 'coloring_contact_cutomizer' );
//アーカイブページのタイトル
function coloring_archive_title() {
  $title = get_bloginfo( 'name' );
  $subtitle =null;
  if ( is_category() ) {
        $title = 'GATEGORY';
        $subtitle = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = 'TAG';
        $subtitle = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = 'AUTHOR';
        $subtitle = get_the_author();
    } elseif ( is_year() ) {
        $title = 'YEAR';
        $subtitle = get_the_date('Y年');
    } elseif ( is_month() ) {
        $title = 'MONTH';
        $subtitle = get_the_date('Y年n月') ;
    } elseif ( is_day() ) {
        $title = 'DAY';
        $subtitle = get_the_date('Y年n月j日') ;
  } elseif ( is_search() ) {
        $title = get_search_query();
        $subtitle = 'を含む記事';
    } elseif ( is_404() ) {
        $title = '<img src="'.get_template_directory_uri().'/img/404/404_ttl.png" alt="404" width="147.16px" height="39.17" class="ttl-group__img"/>' ;
        $subtitle = 'お探しのページが見つかりません';
    }
    $titles = ['title' => $title, 'subtitle' => $subtitle];
  return $titles;
}
//////////////////////////////////////////////////
//wp_head　<title>タグの設定
//////////////////////////////////////////////////
// wp_headで<title>を出力する
function setup_theme() {
	add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'setup_theme' );

// <title>の区切り線を｜に変更する
function coloring_title_separator(){
    $sep = '│';
    return $sep;
}
add_filter( 'document_title_separator', 'coloring_title_separator' );

// <title>の設定
function coloring_document_title( $title ) {
	if ( is_home() ) {
		if ( get_option('coloring_seo_titleTop') && !get_option('coloring_seo_titleTopName') ) {
			$title = get_option('coloring_seo_titleTop');
		}elseif ( get_option('coloring_seo_titleTop') && get_option('coloring_seo_titleTopName') ) {
			$title = get_option('coloring_seo_titleTop') .coloring_title_separator() .get_bloginfo( 'name' );		
		}else {
			$title = get_bloginfo( 'description' ) .coloring_title_separator() .get_bloginfo( 'name' );
		}
	}
	if (is_category() || is_tag() || is_author() || is_year() || is_month() || is_day() || is_search() || is_404() ) {
        $title = coloring_archive_title() .coloring_title_separator() .get_bloginfo( 'name' );
	}
	if (is_singular() && get_post_meta(get_the_ID(), 'title', true) && get_post_meta(get_the_ID(), 'titleName', true) ) {
        $title = get_post_meta(get_the_ID(), 'title', true) .coloring_title_separator() .get_bloginfo( 'name' );
    }
	if (is_singular() && get_post_meta(get_the_ID(), 'title', true) && !get_post_meta(get_the_ID(), 'titleName', true) ) {
        $title = get_post_meta(get_the_ID(), 'title', true);
    }
	return $title;
}
add_filter( 'pre_get_document_title', 'coloring_document_title' );
//wp_headにオリジナル項目追加
function coloring_head() {
  if ( get_option('coloring_seo_cssLoad') == "value2" && get_option('coloring_seo_cssLoad-main')) {
    echo '<link class="css-async" rel href="'.get_template_directory_uri().'/css/normalize.css">'."\n";
		echo '<link class="css-async" rel href="'.get_stylesheet_uri().'">'."\n";
		echo '<link class="css-async" rel href="'.get_template_directory_uri().'/css/style.css">'."\n";
	}else{
    echo '<link rel="stylesheet" rel href="'.get_template_directory_uri().'/css/normalize.css">'."\n";
		echo '<link rel="stylesheet" href="'.get_stylesheet_uri().'">'."\n";
    echo '<link rel="stylesheet" rel href="'.get_template_directory_uri().'/css/style.css">'."\n";
	}
  echo '<link rel="stylesheet" href="https://use.typekit.net/gjf3dgf.css">'."\n";
  //IE最新表示
  echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">'."\n";
  //viewport
	echo '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">'."\n";
  //dns-prefetch
  echo '<link rel="dns-prefetch" href="//www.google.com">'."\n";
	echo '<link rel="dns-prefetch" href="//www.google-analytics.com">'."\n";
	echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">'."\n";
	echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">'."\n";
	echo '<link rel="dns-prefetch" href="//pagead2.googlesyndication.com">'."\n";
	echo '<link rel="dns-prefetch" href="//googleads.g.doubleclick.net">'."\n";
	echo '<link rel="dns-prefetch" href="//www.gstatic.com">'."\n";
  //ファビコン
  echo '<link rel="shortcut icon" href="'.get_template_directory_uri().'/img/coloring_icon.svg" />';
  echo '<link rel="apple-touch-icon" sizes="180x180" href="'.get_template_directory_uri().'/img/coloring_icon.svg">';
  echo '<link rel="icon" type="image/png" href="'.get_template_directory_uri().'/img/coloring_icon.svg" sizes="192x192">';
  if (is_single()){
		wp_enqueue_script("comment-reply");//コメントの返信フォーム設定
  }
}
add_action('wp_head', 'coloring_head');
//OGP設定
function setting_ogp(){
  echo '<meta property="og:site_name" content="'.get_bloginfo('name').'" />'."\n";
  //投稿(post)、カスタム投稿タイプ、固定ページ、添付ファイルのシングルページ
  if(is_singular()){
    echo '<meta property="og:type" content="article" />'."\n";
  }else{
    echo '<meta property="og:type" content="website" />'."\n";
  }
  //投稿(post)、カスタム投稿タイプ、固定ページ、添付ファイルのシングルページ
  if (is_singular()){
    //ページタイトル
    echo '<meta property="og:title" content="'.get_the_title().'" />'."\n";
    if(have_posts()){while ( have_posts() ) { the_post();
      //ページの説明文
      global $post;
      $post_id = $post->ID;
			echo '<meta property="og:description" content="'.get_post_meta($post_id, '_aioseop_description', true).'" />'."\n";
		}}
    //ページURL
    echo '<meta property="og:url" content="'.get_the_permalink().'" />'."\n";
  //TOPページ
  }elseif (is_home()){
    //ページタイトル
    echo '<meta property="og:title" content="'.get_bloginfo('name').'" />'."\n";
    //ページの説明文
    echo '<meta property="og:description" content="'.get_bloginfo('description').'" />'."\n";
    //ページURL
    echo '<meta property="og:url" content="'.get_home_url().'" />'."\n";
  }else{
    //ページタイトル
    echo '<meta property="og:title" content="'.wp_get_document_title().'" />'."\n";
    //ページの説明文
    if (term_description()) {//タームのディスクリプション
      echo '<meta property="og:description" content="'.term_description().'" />'."\n";
    }else{
      echo '<meta property="og:description" content="'.get_bloginfo('description').'" />'."\n";
    }//ページURL
    if(is_year()){//年別アーカイブ
      echo '<meta property="og:url" content="'.get_year_link('').'" />'."\n";
    }elseif(is_month()){//月別アーカイブ
			echo '<meta property="og:url" content="'.get_month_link('', '').'" />'."\n";
		}elseif(is_day()){//日別アーカイブ
			echo '<meta property="og:url" content="'.get_day_link('', '', '').'" />'."\n";
		}elseif(is_author()){//作成者アーカイブページ
			echo '<meta property="og:url" content="'.get_author_posts_url(get_the_author_meta( 'ID' )).'" />'."\n";
		}elseif(is_search()){//検索結果ページ
			echo '<meta property="og:url" content="'.get_search_link().'" />'."\n";
		}elseif(is_category()){//カテゴリーページ
			$cat = get_the_category();
			$cat_id = $cat[0]->cat_ID;
			echo '<meta property="og:url" content="'.get_category_link($cat_id).'" />'."\n";
		}elseif(is_tag()){//タグページ
			$tag = get_the_tags();
			$tag_id = $tag[0]->term_id;
			echo '<meta property="og:url" content="'.get_tag_link($tag_id).'" />'."\n";
		}else{//その他
			echo '<meta property="og:url" content="'.get_home_url().'" />'."\n";
		}
  }
  //画像
  if (is_singular()){  //投稿(post)、カスタム投稿タイプ、固定ページ、添付ファイルのシングルページ
    if (has_post_thumbnail()){//投稿にサムネイルがある場合
      $image_id = get_post_thumbnail_id();//thumbnail ID
      $image = wp_get_attachment_image_src( $image_id, 'icatch');//thumbnail src
      echo '<meta property="og:image" content="'.$image[0].'" />'."\n";
    }elseif(get_coloring_image_ogp()){//投稿にサムネイルが無く、OGP用画像がある場合
			echo '<meta property="og:image" content="'.get_coloring_image_ogp().'" />'."\n";
		}else{//何も無い場合
			echo '<meta property="og:image" content="'.get_template_directory_uri().'/img/archive/thumnail_min.jpg" />'."\n";
		}
  }else{//その他
    echo '<meta property="og:image" content="'.get_coloring_image_ogp().'" />'."\n";
  }
		echo '<meta name="twitter:card" content="'.get_option('coloring_social_TwitterCard').'" />'."\n";
  if ( get_option('coloring_sns_twitter')) {
		echo '<meta name="twitter:site" content="@'.get_option('coloring_sns_twitter').'" />'."\n";
	}
	
	if ( get_option('coloring_social_FBAppId')) {
		echo '<meta property="fb:app_id" content="'.get_option('coloring_social_FBAppId').'" />'."\n";
	}
	
	if ( get_option('coloring_social_FBAdmins')) {
		echo '<meta property="fb:admins" content="'.get_option('coloring_social_FBAdmins').'" />'."\n";
	}
}
//sns表示設定
function coloring_sns($ul_class){
  $sns_lists_keys = array_keys(sns_lists());
  $inset_snss = array();
  foreach($sns_lists_keys as $sns_name){
    if(!empty(get_option('coloring_sns_'.$sns_name))){
      array_push($inset_snss,$sns_name);
    }
  }
  foreach($inset_snss as $inset_sns){
      if($inset_sns === $inset_snss[0]){
        if(empty($ul_class)){
          echo '<ul class="sns__list">';
        }else{
          echo '<ul class="sns__list--'.$ul_class.'">'; 
        }
      }
      echo '<li class="sns__list-item"><a href="'.
      sns_lists()[$inset_sns]['url'].get_option('coloring_sns_'.$inset_sns).'" target="_blank" rel="noopener" class="sns__list-link--'.$ul_class.'"><i class="fab fa-'.sns_lists()[$inset_sns]['font'].'"></i></a></li>';
      if ($inset_sns === end($inset_snss)) {
        echo '</ul>';
      }
  }  
}
//固定ページリンク
  function page_link($ul_class_Nmae,$li_class_Nmae){
    $inset_pages = array();
    foreach(page_list() as $page_item){
      if(!get_option('coloring_'.$li_class_Nmae.'-link-id'.get_the_title($page_item->ID))){
        array_push($inset_pages,$page_item);
      }
    }
    foreach ( $inset_pages as $inset_page) {
      if ($inset_page === reset($inset_pages)) {
        echo '<ul class="'.$ul_class_Nmae.'">';
      }
        echo '<li class="'.$li_class_Nmae.'__list-item"><a href="'. get_page_link($inset_page->ID).'" class="'.$li_class_Nmae.'__list-link">'.get_the_title($inset_page->ID).'</a></li>'; 
      if ($inset_page === end($inset_pages)) {
          echo '</ul>';
      } 
    }
}
//SNSボタンリスト
function coloring_share_btn(){
  $options = get_option('coloring_post_share');
  if ( $options['facebook'] || $options['twitter'] || $options['google'] || $options['hatebu'] || $options['pocket'] || $options['line'] || ['url'] ) {
    echo '<aside><h3 class="content__ttl3--punctuation">この記事をシェアする</h3>'."\n";
    echo '<ul class="social__aside-main--flex">'."\n";
    foreach (sns_share_lists() as  $sns_share_ttl => $sns_share_date) { 
      //title表示切り替え
      if($sns_share_date['options'] === 'hatebu'){
        $sns_share_titleBefore = 'このエントリーを';
        $sns_share_title ='に追加';
      }elseif($sns_share_date['options'] === 'line'){
        $sns_share_title = 'で送る';
      }elseif($sns_share_date['options'] === 'share'){
        $sns_share_title = 'クリップボードにURLをコピー';
        $sns_share_option = 'data-clipboard-text="'.get_permalink().'"onclick="copyUrl()";return false;';
      }else{
        $sns_share_title = 'で共有';
      }
      if ( $options[''.$sns_share_date['options'].''] ) {
        echo '<li class="social__list-item"><a class="social__list-link--'.$sns_share_date['options'].'" href="'.$sns_share_date['url'] .'" title="'.$sns_share_titleBefore.$sns_share_ttl.$sns_share_title.'"'.$sns_share_option.'><i class="fab fa-'.$sns_share_date['font'].'"></i></a></li>';
      }
    }
    echo '</ul><p id="copy-txt">URLをコピーしました。</p>'."\n";
		echo '</aside>'."\n";
  }
}
//ABOUTページ表示設定
  //コンセプト
  function coloring_about_concept(){
    $coloring_about_concept_img = array();//イメージが入る配列
    $coloring_about_concept_ttl = array(); //タイトルが入る配列
    $coloring_about_concept_desc = array();//説明文が入る配列
    for($concept_no = 1; $concept_no <= concept_counter(); $concept_no++){
      //イメージ
        if ( esc_url(get_theme_mod('coloring_about_concept_img'.$concept_no)) ){
          array_push($coloring_about_concept_img,esc_url(get_theme_mod('coloring_about_concept_img'.$concept_no)));
        }else{
          array_push($coloring_about_concept_img,null);
        }//endif
      //タイトル
        if (get_option('coloring_about_concept_ttl'.$concept_no)){
          array_push($coloring_about_concept_ttl,get_option('coloring_about_concept_ttl'.$concept_no));
        }//endif
      //説明文  
        if ( get_option('coloring_about_concept_desc'.$concept_no) ){
          array_push($coloring_about_concept_desc,get_option('coloring_about_concept_desc'.$concept_no));
        }//endif
  }//endfor
  $about_concept = [
    'img' => $coloring_about_concept_img,
    'ttl' => $coloring_about_concept_ttl,
    'desc' => $coloring_about_concept_desc];
  return $about_concept;
}
//お問い合わせページ表示設定
  //注意事項
  function coloring_contact_caution(){
    //注意事項リスト
      $caution_list = array();//お問い合わせページ
      $sent_caution_list = array();//お問い合わせ完了ページ
    for($caution_no = 1; $caution_no <= caution_conter(); $caution_no++){
      if(get_option('coloring_contact_caution'.$caution_no)){
        array_push($caution_list,get_option('coloring_contact_caution'.$caution_no));
      }
      if(get_option('coloring_contact_sentcaution'.$caution_no)){
        array_push($sent_caution_list,get_option('coloring_contact_sentcaution'.$caution_no));
      }
    }
    $caution_txt = [
      'caution' =>    $caution_list,
      'sent' => $sent_caution_list
    ];
  return $caution_txt;
}
//wordpress関数処理
  //get_the_excerpt()
  function twpp_change_excerpt_more( $more ) {
    return '';
  }
  add_filter( 'excerpt_more', 'twpp_change_excerpt_more' );
  //目次の表示/非表示、個別選択設定
    if ( get_option('coloring_post_outline') != 'value2') {
      //ボックス
      function add_outline_fields() {
        add_meta_box( 'outline_setting', '目次の個別非表示設定', 'insert_outline_fields', 'post', 'normal');
      }
      add_action('admin_menu', 'add_outline_fields');
      //入力エリア
      function insert_outline_fields() {
        global $post;
      
        if( get_post_meta($post->ID,'outline_none',true) == "1" ) {
          $outline_none_check = "checked";
        }else {
          $outline_none_check = "";
        }
        echo '
          <div style="margin:20px 0; overflow: hidden; line-height:2;">
            <div style="float:left;width:120px;">目次の表示設定</div>
            <div style="float:right;width:calc(100% - 140px);">
              <input type="checkbox" name="outline_none" value="1" '.$outline_none_check.' >:この投稿では目次を非表示にしますか？
            </div>
            <div style="clear:both;"></div>
          </div>
        ';
      }
      //値を保存
      function save_outline_fields( $post_id ) {
        if(!empty($_POST['outline_none'])){
          update_post_meta($post_id, 'outline_none', $_POST['outline_none'] );
        }else{
          delete_post_meta($post_id, 'outline_none');
        }
    
      }
      add_action('save_post', 'save_outline_fields');
    }
//オリジナル関数
  //PCスマホ分岐
    function is_mobile(){
      $useragents = array(
          'iPhone', // iPhone
          'iPod', // iPod touch
          'Android.*Mobile', // 1.5+ Android *** Only mobile
          'Windows.*Phone', // *** Windows Phone
          'dream', // Pre 1.5 Android
          'CUPCAKE', // 1.5+ Android
          'blackberry9500', // Storm
          'blackberry9530', // Storm
          'blackberry9520', // Storm v2
          'blackberry9550', // Storm v2
          'blackberry9800', // Torch
          'webOS', // Palm Pre Experimental
          'incognito', // Other iPhone browser
          'webmate' // Other iPhone browser
      );
      $pattern = '/'.implode('|', $useragents).'/i';
      return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
  }
  // パンくずリスト
function breadcrumb(){
  global $post;
  $str ='';
  if(!is_home()&&!is_admin()){
      $str.= '<div id="breadcrumb" class="breadcrumb"><div itemscope itemtype="http://data-vocabulary.org/Breadcrumb" style="display:table-cell;">';
      $str.= '<a href="'. home_url() .'" itemprop="url" class="breadcrumb__link"><span itemprop="title">ホーム</span></a> &gt;&#160;</div>';
      if(is_category()) {
          $cat = get_queried_object();
          if($cat -> parent != 0){
              $ancestors = array_reverse(get_ancestors( $cat -> cat_ID, 'category' ));
              foreach($ancestors as $ancestor){
$str.='<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb" style="display:table-cell;"><a href="'. get_category_link($ancestor) .'" itemprop="url" class="breadcrumb__link-ispage"><span itemprop="title">'. get_cat_name($ancestor) .'</span></a> &gt;&#160;</div>';
              }
          }
$str.='<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb" style="display:table-cell;"><a href="'. get_category_link($cat -> term_id). '" itemprop="url" class="breadcrumb__link-ispage"><span itemprop="title">'. $cat-> cat_name . '</span></a></div>';
      } elseif(is_page()){
          if($post -> post_parent != 0 ){
              $ancestors = array_reverse(get_post_ancestors( $post->ID ));
              foreach($ancestors as $ancestor){
                  $str.='<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb" style="display:table-cell;"><a href="'. get_permalink($ancestor).'" itemprop="url" class="breadcrumb__link-ispage"><span itemprop="title">'. get_the_title($ancestor) .'</span></a> &gt;&#160;</div>';
              }
          }
      } elseif(is_single()){
          $categories = get_the_category($post->ID);
          $cat = $categories[0];
          if($cat -> parent != 0){
              $ancestors = array_reverse(get_ancestors( $cat -> cat_ID, 'category' ));
              foreach($ancestors as $ancestor){
                  $str.='<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb" style="display:table-cell;"><a href="'. get_category_link($ancestor).'" itemprop="url"  class="breadcrumb__link"><span itemprop="title">'. get_cat_name($ancestor). '</span></a> &gt;&#160;</div>';
              }
          }
          $str.='<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb" style="display:table-cell;"><a href="'. get_category_link($cat -> term_id). '" itemprop="url" class="breadcrumb__link-ispage"><span itemprop="title">'. $cat-> cat_name . '</span></a></div>';
      } else{
          $str.='<div>'. wp_title('', false) .'</div>';
      }
      $str.='</div>';
  }
  echo $str;
  }
    // カテゴリー一覧
    function cats($class){ 
      $cats_isset = array();
      foreach (cat_lists() as $category ) {
        if(get_option('coloring_'.$class.'_cat_'.$category->cat_ID)){
          array_push($cats_isset,$category->cat_ID);
        }
      }
      $catParent = array(
        'parent' => 0,
        'orderby' => 'trem_order',
        'order' => 'ASC',
        'exclude' => $cats_isset
      );
        $categorys = get_categories( $catParent );
        foreach($categorys as $cat){
          if ($cat === reset($categorys)){
            echo '<ul class="'.$class.'__list">';
          }
          echo '<li class="'.$class.'__list-item">
            <a href="'.get_category_link( $cat->term_id ).'" class="'.$class.'__list-link">'. $cat->name.'</a>
          </li>';
          if ($cat === end($categorys)){
            echo '</ul>';
          }
        }
      }
    // カテゴリー一覧
    function exclusion_cat_ids($number_of_posts){ 
      $exclusion_cat_ids = array();
      foreach (cat_lists() as $category ){
        if(get_option('coloring_loop_'.$category->cat_ID)){
          array_push($exclusion_cat_ids,$category->cat_ID);
        }
      }
      $paged = get_query_var('paged') ? get_query_var('paged') : 1;
      $loop_query_condi = array(
        'post_type' => 'post',
        'orderby' => 'modified',
        'order' => 'DESC',
        'posts_per_page' => $number_of_posts,
        'category__not_in' => $exclusion_cat_ids,
        'paged' => $paged
      );
      return $loop_query_condi;
    }
    //目次
    function get_outline_info($content){
      if(is_single()){
        $outline = ''; //目次のHTMLを入れる変数
        $counter = 0; //h1〜h6タグの個数を入れる変数
        // 記事内のh1〜h6タグを検索します。(idやclass属性も含むように改良)
        if (preg_match_all('/<h([1-6])[^>]*>(.*?)<\/h\1>/', $content, $matches,  PREG_SET_ORDER)) {
          // 記事内で使われているh1〜h6タグの中の、1〜6の中の一番小さな数字を取得
          // ※以降ソースの中にある、levelという単語は1〜6のことを表す
          $min_level = min(array_map(function($m) { return $m[1]; }, $matches));
          // スタート時のlevelを決定
          // ※このレベルが上がる毎に、<ul></li>タグが追加
          $current_level = $min_level - 1;
          // 各レベルの出現数を格納する配列を定義
          $sub_levels = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0);
          // 記事内で見つかった、hタグの数だけループ
          foreach ($matches as $m) {
            $level = $m[1];  // 見つかったhタグのlevelを取得
            $text = $m[2];  // 見つかったhタグの、タグの中身
            // li, ulタグを閉じる処理。2ループ目以降に中に入る可能性があるため。
            // 例えば、前回処理したのがh3タグで、今回出現したのがh2タグの場合、
            // h3タグ用のulを閉じて、h2タグに備える。
            while ($current_level > $level) {
              $current_level--;
                  $outline .= '</li></ul>';
            }
            // 同じlevelの場合、liタグを閉じ、新しく開く
            if ($current_level == $level) {
              $outline .= '</li><li class="outline__item'.$current_level.'">';
            }else{
              // 同じlevelでない場合は、ul, liタグを追加。
                // 例えば、前回処理したのがh2タグで、今回出現したのがh3タグの場合、
                // h3タグのためにulを追加。
                while ($current_level < $level) {
                  $current_level++;
                  $outline .= sprintf('<ul class="outline__list-%s"><li class="outline__item'.$current_level.'">', $current_level);
                }
                // 見出しのレベルが変わった場合は、現在のレベル以下の出現回数をリセットします。
                for ($idx = $current_level + 0; $idx < count($sub_levels); $idx++) {
                  $sub_levels[$idx] = 0;
                }
              }
              // 各レベルの出現数を格納する配列を更新
              $sub_levels[$current_level]++;
              // 現在処理中のhタグの、パスを入れる配列を定義
              // 例えば、h2 -> h3 -> h3タグと進んでいる場合は、
              // level_fullpathはarray(1, 2)のようになる
              // ※level_fullpath[0]の1は、1番目のh2タグの直下に入っていることを表す
              // ※level_fullpath[1]の2は、2番目のh3を表す
              $level_fullpath = array();
              for ($idx = $min_level; $idx <= $level; $idx++) {
                  $level_fullpath[] = $sub_levels[$idx];
              }
              $level_fullpath_count = count($level_fullpath);//配列の個数
              if($level_fullpath_count > 2){
                $outeline_icon = '';
              }elseif($level_fullpath_count > 1){
                $outeline_icon = '-';
              }else{
                $outeline_icon = '<i class="fas fa-square-full"></i>';
              }
              $target_anchor = 'outline__' . implode('_', $level_fullpath);
              // 目次に、<a href="#outline_1_2">1.2 見出し</a>のような形式で見出しを追加
              $outline .= sprintf('<a class="outline__link" href="#%s"><span class="outline__number">%s</span> %s</a>', $target_anchor, $outeline_icon, strip_tags($text));
              // 本文中の見出し本体を、<h3>見出し</h3>を<h3 id="outline_1_2">見出し</h3>
              // のような形式で置き換え
              $hid = preg_replace('/<h([1-6])/', '<h\1 id="' .$target_anchor . '"', $m[0]);
              $content = str_replace($m[0], $hid, $content);
          }
          // hタグのループが終了後、閉じられていないulタグを閉じる。
          while ($current_level >= $min_level) {
            $outline .= '</li></ul>';
            $current_level--;
          }
          // h1〜h6タグの個数
          $counter = count($matches);
        }
        return array('content' => $content, 'outline' => $outline, 'count' => $counter);
      }  
    }
      //目次を作成  
      function add_outline($content) {
        if(is_single()){
            // 目次を表示するために必要な見出しの数
            if(get_option('coloring_post_outline_number')){
              $number = get_option('coloring_post_outline_number');
            }else{
              $number = 1;
            }
            // 目次関連の情報を取得します。
            $outline_info = get_outline_info($content);
            $content = $outline_info['content'];
            $outline = $outline_info['outline'];
            $count = $outline_info['count'];
            if (get_option('coloring_post_outline_close') ) {
              $close = "";
            }else{
              $close = "checked";
            }
            if ($outline != '' && $count >= $number) {
              // 目次を装飾
              $decorated_outline = sprintf('
          <div class="outline">
            <input class="outline__toggle" id="outline__toggle" type="checkbox" '.$close.'>
            <label class="outline__switch--middle" for="outline__toggle">目次</label>
            %s
          </div>', $outline);
            // カスタマイザーで目次を非表示にする以外が選択された時＆個別非表示が1以外の時に目次を追加
            if ( get_option('coloring_post_outline') != 'value2' && get_post_meta(get_the_ID(), 'outline_none', true) != '1' && is_single() ) {
              $shortcode_outline = '[outline]';
              if (strpos($content, $shortcode_outline) !== false) {
                  // 記事内にショートコードがある場合、ショートコードを目次で置換します。
                  $content = str_replace($shortcode_outline, $decorated_outline, $content);
              } else if (preg_match('/<h[1-6].*>/', $content, $matches, PREG_OFFSET_CAPTURE)) {
                  // 最初のhタグの前に目次を追加します。
                  $pos = $matches[0][1];
                  $content = substr($content, 0, $pos) . $decorated_outline . substr($content, $pos);
              }
            }
          }
        }  
          return $content;  
      }
      add_filter('the_content', 'add_outline');
   //投稿スラッグ（固定ページは除く） から 投稿idを取得。
    function get_post_id_by_slug($post_slug){
      $args=array(
        'name' => $post_slug,
        'post_type' => 'post',
        'post_status' => 'publish',
        'numberposts' => 1
      );
      $found_posts = get_posts($args);
      if( $found_posts ) {
        return $found_posts[0]->ID;
      }else{
        return NULL;
      }
    }   
  //single.php add class
  function add_class_single($content) {
    if(is_single()){//投稿ページのみ
      //指定のタグを$matches配列へ追加
        //hタグ検索 
        if (preg_match_all('/<h(\d)/', $content, $matches,  PREG_SET_ORDER)) {
          $min_level = min(array_map(function($m) { return $m[1]; }, $matches));//hタグの最小数字
          $current_level = $min_level  ;//スタート時のhタグの数字
          foreach ($matches as $m) {
            $level = $m[1];  // 見つかったhタグの数字
            //hタグ
              //前より数字が小さくなった場合
              if ($current_level > $level) {
                $current_level--;
              }
              //前より数字が大きくなった場合
              elseif ($current_level < $level) {
                $current_level++;
              }
              //class追加 content__ttl.$current_level
              $content = str_replace('<h'.$current_level, '<h'.$current_level.' class="content__ttl'.$current_level.'--punctuation"', $content);
          }
        }
        //class追加
          //追加するclass名
          $addClassNmaes = array('p' => 'content__txt','img' => 'content__img','ul' => 'content__ul','ol' => 'content__ol','li' => 'content__li','blockquote' => 'content__blockquote','table' => 'content__table','tbody' => 'content__tbody','tr' => 'content__tr','th' => 'content__th','td' => 'content__td','iframe' => 'content__iframe');
          foreach($addClassNmaes as $tag => $addClassNmae){
            if(strpos($content,'<'.$tag.'>')!== false){
              $content = str_replace('<'.$tag.'>','<'.$tag.' class="'.$addClassNmae.'">', $content);
            }elseif(strpos($content,'<img ')!== false || strpos($content,'<iframe ')!== false){
              if($tag === 'img' || $tag === 'iframe'){
              $content = str_replace('<'.$tag,'<'.$tag.' class="'.$addClassNmae.'" ', $content);
              }
            }
          }
    return $content;
  }else{
    return $content;
  }
  }
  add_filter('the_content', 'add_class_single');
  //imgをpタグで囲わない
function filter_ptags_on_images($content){
return preg_replace('/<p class="content__txt">\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}
add_filter('the_content', 'filter_ptags_on_images');
  //Advanced Custom Fields表示
    function acf_roop_display($filed_id){//同じ入力項目を繰り返すフィールド用
      $field_counter = count(acf_get_fields($filed_id));//項目数
      $field_parents = get_field(acf_get_fields($filed_id)[1]["name"]);//1つめの親フィールド情報を取得
      $field_parent_keys = array_keys($field_parents); //親のキー取得(フィールド名)
      foreach($field_parents as $field_parent_key =>$field_parent){//親のキー(フィールド名)と値
        if(array_keys($field_parent)){//子フィールドがある場合
          $field_havechild_keys = $field_parent_key;
          $field_child_keys = array_keys($field_parent);//子フィールド名取得
          $field_keys_index = array_search($field_parent_key, $field_parent_keys);//子フィールドがある親フィールド名の配列番号取得
          unset($field_parent_keys[$field_keys_index]);//子フィールドがある親フィールド名の削除
        }
      }
      $acf =['field_counter'=>$field_counter,'field_parent_keys'=>$field_parent_keys,'field_havechild_keys'=>$field_havechild_keys,'field_child_keys'=>$field_child_keys];
      return  $acf;//[フィールド数,一番上のフィール名配列,下に更にフィールドを持っているフィールド名,子フィールド名]
    }
    function acf_display($acf_id){//それぞれちがう入力項目フィールド用
      $field1_counter = count(acf_get_fields($acf_id));//一番上の入力項目数
      $field1_lists= array();//一番上のフィールド
           for($field1_count = 0; $field1_count < $field1_counter; $field1_count++){
        $fields1_count_id =acf_get_fields($acf_id)[$field1_count];
        $field1_type=$fields1_count_id['type'];//一番上のラベル名配列
        $field1_name=$fields1_count_id['name'];//一番上のラベル名配列
        $field1_label=$fields1_count_id['label'];//一番上のラベル名配列
        if($field1_type !== 'group'){//タイプがグループ=子項目がある場合は配列に追加しない
          $field1_lists[$field1_label]=$field1_name;
        }else{
          $field2_id_lists = array();
          $field_root_lists = array();
          array_push($field2_id_lists,$fields1_count_id);
          $subfield_counter = count($field2_id_lists);//一番上の入力項目数
          for ($field2_count = 0; $field2_count < $subfield_counter; $field2_count++){
            $field_id_lists = $field2_id_lists[$field2_count];
            $field_sub_lists = $field_id_lists['sub_fields'];
          }
          $field_id_arraylists = array();//['sub_fields']がないリスト
            while($subfield_counter === 1){
              $subfield_counter = count($field_sub_lists);//サブフィールド(2階層目)のフィールド数
              for ($subfield_count = 0; $subfield_count < $subfield_counter; $subfield_count++){
                $field_notsub_list = $field_sub_lists;
                $field_root_list = $field_id_lists;
                $field_id_lists = $field_sub_lists[$subfield_count];
                    $field_sub_lists = $field_id_lists['sub_fields'];
                    array_push($field_id_arraylists,$field_sub_lists);
                  if(!empty($field_sub_lists)){
                    $field_notsub_lists = $field_notsub_list;
                    array_push($field_root_lists,$field_root_list);
                  }
              }
            }
               $lastfield_counter=count($field_id_arraylists);
              $lastfield_lists = array();
              for ($lastfield_count = 0; $lastfield_count < $lastfield_counter; $lastfield_count++){
                $lastfield_name_lists =$field_id_arraylists[0][$lastfield_count]['name'];//これ以上下がない項目の名前 要$field_id_arraylistsの下は共ラベル通名
                array_push($lastfield_lists,$lastfield_name_lists);
              }
          }
      }
      $lastgroup_counter =count($field_notsub_lists);
      $lastgroup_count_name =array();
      for($lastgroup_count = 0; $lastgroup_count < $lastgroup_counter; $lastgroup_count++){
        array_push($lastgroup_count_name ,$field_notsub_lists[$lastgroup_count]['name']);
      }
      $field_root_list=$field_root_lists;
      $field_root_counter=count($field_root_list);
      $field_root_names= array();
      for($field_root_count = 0; $field_root_count < $field_root_counter; $field_root_count++){
        array_push($field_root_names ,$field_root_list[$field_root_count ]['name']);
      }
      $filed1_lists =$field1_lists;//グループじゃないフィールド名
      $lastgroupfiled_name = $lastgroup_count_name ;
      $field_root_name = $field_root_names;//一番最初に['sub_field']をもつフィールド
      $lastgroupfiled_label = $field_notsub_lists[0]['label'];//['type']がgroupの最下層ラベル名(共通項目);
      $lastfiled_name = $lastfield_lists;//下に['sub_field']がないフィード名(最下層)
      $lastfiled_name_counter = count($lastfiled_name);//下に['sub_field']がないフィード名(最下層)の項目数
      $acf_display2 = array(
        'filed1_lists' =>$filed1_lists,
        'field_root_names'=>$field_root_name,
        'lastgroupfiled_names'=>$lastgroupfiled_name, 
        'lastgroupfiled_labels'=>$lastgroupfiled_label,
        'lastfiled_names'=>$lastfiled_name,
        'lastfiled_names_counter'=>$lastfiled_name_counter,
      );
      return  $acf_display2;
    }
    //ページネーション
    function coloring_posts_pagination($pages = '', $range = 2)
    {
      $showitems = ($range * 2)+1;//表示するページ数（5ページを表示）
      global $paged;//現在のページ値
      if(empty($paged)) $paged = 1;//デフォルトのページ
      if($pages == ''){
        global $wp_query;
        $pages = $wp_query->max_num_pages;//全ページ数を取得
        if(!$pages){//全ページ数が空の場合は、１とする
          $pages = 1;
        }
      }
      if(1 != $pages){//全ページが１でない場合はページネーションを表示する
        echo "<div class=\"pagination\">";
        echo "<ul class=\"pagination__list\">";
        //Prev：現在のページ値が１より大きい場合は表示
        if($paged > 1){}
        for ($i=1; $i <= $pages; $i++){
          if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
            //三項演算子での条件分岐
            echo ($paged == $i)? '<li class="pagination__list-item"><a class="pagination__list-item-link--paged" href="'.get_pagenum_link($i).'">'.$i.'</a></li>':'<li class="pagination__list-item"><a href="'.get_pagenum_link($i).'" class="pagination__list-item-link">'.$i.'</a></li>';
          }
        }
        //表示する項目よりも全ページ数が多い場合
        if($pages > $showitems && $paged < $pages){
          $counter_pageed_digit=mb_strlen($paged);//現在のページ桁数
          $zero= NULL;
          $change_no= 10-(($showitems-1)/2);//10,20...の表示をかえる区切り
          if($counter_pageed_digit < 2){//一桁の場合$pagination_lastlistに10を表示するために一番左の桁1
            $pageed_digit=1;//
          }else{
            $pageed_digit=mb_substr( $paged, 0, 1)+1;//$pagination_lastlistにそのページの一番左の桁の数字+1
          }
          $change_no_criterion = mb_substr( $paged, $counter_pageed_digit-1, 1);//10,20...の表示をかえる区切りのページかチェック
          $change_no_criterion = intval($change_no_criterion);
          if($change_no_criterion === $change_no){
            $pageed_digit = $pageed_digit+1;
          }
          for($count_pageed_digit=0; $count_pageed_digit < $counter_pageed_digit; $count_pageed_digit++){//現在のページ桁数分0を追加
            $zero.=0;
          }
          $pagination_lastlist=intval($pageed_digit.$zero);//0を追加したのを数字に変換
          if($pages >$pagination_lastlist){
          echo '<li class="pagination__list-item">&hellip;</li>';
          echo '<li class="pagination__list-item"><a href="'.get_pagenum_link($pagination_lastlist).'" class="pagination__list-item-link">'.$pagination_lastlist.'</a></li>';//0を追加したのを表示(そのページ数まである場合)
          }
        }
        //Next：総ページ数より現在のページ値が小さい場合は表示
        if ($paged < $pages) echo '<li class="pagination__next"><a href="'.get_pagenum_link($paged + 1).'" class="pagination__next-link"><i class="fas fa-angle-double-right"></i></a></li>';
        echo "</ul>";
        echo "</div>";
      }
    }
    //紹介商品購入先リンクボタンのテキスト
    function buy_btn_txt(){
      $buy_link_txts = array();
      for($buy_link_txt_count = 1; $buy_link_txt_count <= (get_option('coloring_buy_btn_count')); $buy_link_txt_count++){
        $buy_link_txt = get_option('coloring_buy_link_txt'.$buy_link_txt_count);
        array_push($buy_link_txts,$buy_link_txt);
      }
      return $buy_link_txts;
    }
    //エディター
      //スタイルセレクトボタンを追加
      function tinymce_add_buttons( $array ) {
        array_unshift( $array,
          'styleselect'
        );
        return $array;
      }
      add_filter( 'mce_buttons', 'tinymce_add_buttons' );
      //スタイルセレクトの初期設定を変更
      function customize_tinymce_settings($mceInit) {
        $style_formats = array(
          array(
            'title' => '見出し2',
            'block' => 'h2',
            'classes' => 'content__ttl2--punctuation',
          ),
          array(
            'title' => '見出し3',
            'block' => 'h3',
            'classes' => 'content__ttl3--punctuation',
          ),
          array(
            'title' => '見出し4',
            'block' => 'h4',
            'classes' => 'content__ttl4--punctuation',
          ),
          array(
            'title' => '段落',
            'block' => 'p',
            'classes' => 'content__txt',
          ),
        );
        $mceInit['style_formats'] = json_encode( $style_formats );
        return $mceInit;
      }
      add_filter( 'tiny_mce_before_init', 'customize_tinymce_settings' );
      //クイックタグボタン追加
      // 作成したプラグインを登録
      add_filter( 'mce_external_plugins', function ( $plugin_array ) {
        $plugin_array[ 'original_tinymce_button_plugin' ] = get_template_directory_uri() . '/js/tinymce-plugin.js';
        return $plugin_array;
      });
      // プラグインで作ったボタンを登録
      add_filter( 'mce_buttons', function ( $buttons ) {
        array_push($buttons,'insert_linkbtn','insert_caution','insert_caution-no','insert_marker','insert_chek-mark-list','insert_check-item','insert_check-item-not','insert_img_desc','insert_post_link','insert_blockquote','insert_product-link','insert_img-mobile','insert_gif-img','insert_update-day','insert_point','insert_app-btn','insert_cal-btn','insert_raw_material','insert_table-sp','insert_list_price');
        return $buttons;
      });
      function wpdocs_theme_add_editor_styles() {
        add_editor_style( get_template_directory_uri() . '/css/editor-style.css' );
      }
      add_action( 'admin_init', 'wpdocs_theme_add_editor_styles' );
    //オリジナルショートコード
      //関連記事リンク
      function post_link($urls) {
        $link_contents = array();
        foreach($urls as $url){
          $post_url = $url;
          $post_ID = url_to_postid($post_url);
          $post = get_post($post_ID);
          $post_title = get_the_title($post);
          $post_thumbnail=get_the_post_thumbnail($post_ID , 'post-list', array( 'class' => 'archive__img' )); 
          $link_content ='
          <li class="archive__inside-item">
            <a class="archive__thumbnail-left" href="'.$url.'" title="$post_title">'.$post_thumbnail.'</a>
            <div class="archive__inside-right">
                <p class="archive__ttl--min">
                  <a href="'.$url.'" class="archive__ttl-link">'.$post_title.'</a>
                </p> 
            </div>
            <a class="archive__inside-vector">
              <img src="'.get_template_directory_uri().'/img/button/link_icon2.svg" width="19px" height="8px" class="archive__inside-icon">
            </a>
          </li>
          ';
          array_push($link_contents,$link_content);     
        }
        $all_link_contents =implode('', $link_contents);
        $first_loop = '<ul class="content__aside-inside">';
        $end_loop ='</ul>';
        return $first_loop.$all_link_contents.$end_loop;
      }
      add_shortcode('post_link', 'post_link');
      //テキストがエディタにクイックタグボタン追加
  if ( !function_exists( 'add_quicktags_to_text_editor' ) ):
    function add_quicktags_to_text_editor() {
    //スクリプトキューにquicktagsが保存されているかチェック
    if (wp_script_is('quicktags')){?>
      <script>
        QTags.addButton('qt-flex','横並び','<div class="content__aside-scroll"><div class="product__aside-main--flex">','</div></div>');
      </script>
    <?php
    }
  }
  endif;
  add_action( 'admin_print_footer_scripts', 'add_quicktags_to_text_editor' );
  //アドセンスをショートコードで読み込む
function show_adsense() {
    $add_type = '<div class="content__add"><script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- display-square-responsive -->
    <ins class="adsbygoogle"
       style="display:block"
       data-ad-client="ca-pub-9726050208313947"
       data-ad-slot="7351424913"
       data-ad-format="auto"
       data-full-width-responsive="true"></ins>
    <script>
       (adsbygoogle = window.adsbygoogle || []).push({});
    </script></div>';
  return $add_type;
}
add_shortcode('adsense', 'show_adsense');
//---------------------------------------------------------------------------
// 記事投稿(編集)画面に更新レベルのボックス追加
//---------------------------------------------------------------------------

/* ボックス追加 */
if( function_exists( 'thk_post_update_level' ) === false ):
function thk_post_update_level() {
	add_meta_box( 'update_level', '更新方法', 'post_update_level_box', 'post', 'side', 'default' );
	add_meta_box( 'update_level', '更新方法', 'post_update_level_box', 'page', 'side', 'default' );
}
add_action( 'admin_menu', 'thk_post_update_level' );
endif;

/* メインフォーム */
if( function_exists( 'post_update_level_box' ) === false ):
function post_update_level_box() {
	global $post;
?>
<div style="padding-top: 5px; overflow: hidden;">
<div style="padding:5px 0"><input name="update_level" type="radio" value="high" checked="checked" />通常更新</div>
<div style="padding: 5px 0"><input name="update_level" type="radio" value="low" />修正のみ(更新日時を変更せず記事更新)</div>
<div style="padding: 5px 0"><input name="update_level" type="radio" value="del" />更新日時消去(公開日時と同じにする)</div>
<div style="padding: 5px 0; margin-bottom: 10px"><input id="update_level_edit" name="update_level" type="radio" value="edit" />更新日時を手動で変更</div>
<?php
	if( get_the_modified_date( 'c' ) ) {
		$stamp = '更新日時: <span style="font-weight:bold">' . get_the_modified_date( __( 'M j, Y @ H:i' ) ) . '</span>';
	}
	else {
		$stamp = '更新日時: <span style="font-weight:bold">未更新</span>';
	}
	$date = date_i18n( get_option('date_format') . ' @ ' . get_option('time_format'), strtotime( $post->post_modified ) );
?>
<style>
.modtime { padding: 2px 0 1px 0; display: inline !important; height: auto !important; }
.modtime:before { font: normal 20px/1 'dashicons'; content: '\f145'; color: #888; padding: 0 5px 0 0; top: -1px; left: -1px; position: relative; vertical-align: top; }
#timestamp_mod_div { padding-top: 5px; line-height: 23px; }
#timestamp_mod_div p { margin: 8px 0 6px; }
#timestamp_mod_div input { border-width: 1px; border-style: solid; }
#timestamp_mod_div select { height: 21px; line-height: 14px; padding: 0; vertical-align: top;font-size: 12px; }
#aa_mod, #jj_mod, #hh_mod, #mn_mod { padding: 1px; font-size: 12px; }
#jj_mod, #hh_mod, #mn_mod { width: 2em; }
#aa_mod { width: 3.4em; }
</style>
<span class="modtime"><?php printf( $stamp, $date ); ?></span>
<div id="timestamp_mod_div" onkeydown="document.getElementById('update_level_edit').checked=true" onclick="document.getElementById('update_level_edit').checked=true">
<?php thk_time_mod_form(); ?>
</div>
</div>
<?php
}
endif;

/* 更新日時変更の入力フォーム */
if( function_exists( 'thk_time_mod_form' ) === false ):
function thk_time_mod_form() {
	global $wp_locale, $post;

	$tab_index = 0;
	$tab_index_attribute = '';
	if ( (int) $tab_index > 0 ) {
		$tab_index_attribute = ' tabindex="' . $tab_index . '"';
	}

	$jj_mod = mysql2date( 'd', $post->post_modified, false );
	$mm_mod = mysql2date( 'm', $post->post_modified, false );
	$aa_mod = mysql2date( 'Y', $post->post_modified, false );
	$hh_mod = mysql2date( 'H', $post->post_modified, false );
	$mn_mod = mysql2date( 'i', $post->post_modified, false );
	$ss_mod = mysql2date( 's', $post->post_modified, false );

	$year = '<label for="aa_mod" class="screen-reader-text">年' .
		'</label><input type="text" id="aa_mod" name="aa_mod" value="' .
		$aa_mod . '" size="4" maxlength="4"' . $tab_index_attribute . ' autocomplete="off" />年';

	$month = '<label for="mm_mod" class="screen-reader-text">月' .
		'</label><select id="mm_mod" name="mm_mod"' . $tab_index_attribute . ">\n";
	for( $i = 1; $i < 13; $i = $i +1 ) {
		$monthnum = zeroise($i, 2);
		$month .= "\t\t\t" . '<option value="' . $monthnum . '" ' . selected( $monthnum, $mm_mod, false ) . '>';
		$month .= $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) );
		$month .= "</option>\n";
	}
	$month .= '</select>';

	$day = '<label for="jj_mod" class="screen-reader-text">日' .
		'</label><input type="text" id="jj_mod" name="jj_mod" value="' .
		$jj_mod . '" size="2" maxlength="2"' . $tab_index_attribute . ' autocomplete="off" />日';
	$hour = '<label for="hh_mod" class="screen-reader-text">時' .
		'</label><input type="text" id="hh_mod" name="hh_mod" value="' . $hh_mod .
		'" size="2" maxlength="2"' . $tab_index_attribute . ' autocomplete="off" />';
	$minute = '<label for="mn_mod" class="screen-reader-text">分' .
		'</label><input type="text" id="mn_mod" name="mn_mod" value="' . $mn_mod .
		'" size="2" maxlength="2"' . $tab_index_attribute . ' autocomplete="off" />';

	printf( '%1$s %2$s %3$s @ %4$s : %5$s', $year, $month, $day, $hour, $minute );
	echo '<input type="hidden" id="ss_mod" name="ss_mod" value="' . $ss_mod . '" />';
}
endif;

/* 「修正のみ」は更新しない。それ以外は、それぞれの更新日時に変更する */
if( function_exists( 'thk_insert_post_data' ) === false ):
function thk_insert_post_data( $data, $postarr ){
	$mydata = isset( $_POST['update_level'] ) ? $_POST['update_level'] : null;

	if( $mydata === 'low' ){
		unset( $data['post_modified'] );
		unset( $data['post_modified_gmt'] );
	}
	elseif( $mydata === 'edit' ) {
		$aa_mod = $_POST['aa_mod'] <= 0 ? date('Y') : $_POST['aa_mod'];
		$mm_mod = $_POST['mm_mod'] <= 0 ? date('n') : $_POST['mm_mod'];
		$jj_mod = $_POST['jj_mod'] > 31 ? 31 : $_POST['jj_mod'];
		$jj_mod = $jj_mod <= 0 ? date('j') : $jj_mod;
		$hh_mod = $_POST['hh_mod'] > 23 ? $_POST['hh_mod'] -24 : $_POST['hh_mod'];
		$mn_mod = $_POST['mn_mod'] > 59 ? $_POST['mn_mod'] -60 : $_POST['mn_mod'];
		$ss_mod = $_POST['ss_mod'] > 59 ? $_POST['ss_mod'] -60 : $_POST['ss_mod'];
		$modified_date = sprintf( '%04d-%02d-%02d %02d:%02d:%02d', $aa_mod, $mm_mod, $jj_mod, $hh_mod, $mn_mod, $ss_mod );
		if ( ! wp_checkdate( $mm_mod, $jj_mod, $aa_mod, $modified_date ) ) {
			unset( $data['post_modified'] );
			unset( $data['post_modified_gmt'] );
			return $data;
		}
		$data['post_modified'] = $modified_date;
		$data['post_modified_gmt'] = get_gmt_from_date( $modified_date );
	}
	elseif( $mydata === 'del' ) {
		$data['post_modified'] = $data['post_date'];
	}
	return $data;
}
add_filter( 'wp_insert_post_data', 'thk_insert_post_data', 10, 2 );
endif;
?>