<!DOCTYPE html>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
<meta charset="<?php bloginfo('charset'); ?>">
<script src="//code.jquery.com/jquery-1.12.1.min.js"></script>
<script src="https://kit.fontawesome.com/802f629b11.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/js/header.js">
</script>
<script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/js/single.js">
</script>
<!-- Google Adsence -->
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-9726050208313947",
    enable_page_level_ads: true
  });
</script>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({
          google_ad_client: "ca-pub-9726050208313947",
          enable_page_level_ads: true
     });
</script>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WXG33NM');</script>
<!-- End Google Tag Manager -->
<!--search-console-->
<meta name="google-site-verification" content="gxbksezjQvikaX8kaU8h20pLI6H1eDGaYyAFLKz7g90" />
<script>
  (function(d) {
    var config = {
      kitId: 'ptc2hub',
      scriptTimeout: 3000,
      async: true
    },
    h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
  })(document);
</script>
<?php wp_head(); ?>
<?php setting_ogp();?>
<?php include_once("analyticstracking.php"); ?>
</head>
<body>
<!--header-->
<header id="header" class="header">
  <div class="header__top">
    <div class="header__search">
      <?php get_search_form() ?>
    </div>  
      <?php
        if(is_singular()){
          $titleMarkup = 'p';
        }else {
          $titleMarkup = 'h1';
        }
      ?>
      <<?php echo $titleMarkup;?> class="header__logo"><a href="/"><img src="<?php echo get_template_directory_uri() ?>/img/logo.png" alt="coloring" width="63.54px" height="80px" class="header__logo-img"/></a></<?php echo $titleMarkup;?>>
    <button type="button" id="header__menu" class="header__menu-outerr" aria-controls="global-nav" aria-expanded="false">
      <span class="header__menu-line">
        <span class="header__menu-u-visuallyHidden">
          メニューを開閉する
        </span>
      </span>
    </button>
  </div>  
<nav class="global-nav">
  <?php // カテゴリー一覧?>
    <?php cats('global-nav');?>
</nav>
<nav id="menu" class="menu">
  <div class="menu__inner">
    <?php cats('menu');?>
        <?php page_link('menu__list',get_option('coloring_header_class'));?>
      <div class="menu__search">
        <?php get_search_form() ?>
      </div>
       <?php coloring_sns('menu'); ?>
  </div>
</nav>
</header>
<!--end-header-->