<?php if(is_page(get_option('coloring_contact_slug'))):?>
            <?php
            //お問い合わせ入力欄自動返信メール設定
            if(isset($_POST['submitted'])) {
              //項目チェック
              if(isset($_POST['checking'])) {
              $captchaError = true;
              }else{
                //名前の入力なし
                if(trim($_POST['contactName']) === '') {
                  $nameError = '名前が入力されていません';
                  $hasError = true;
                }else{
                  $name = trim($_POST['contactName']);
                }
                //メールアドレスの間違い
		            if(trim($_POST['email']) === '') {
                  $emailError = 'メールアドレスが入力されていません';
			            $hasError = true;
                }else if(!preg_match('|^[0-9a-z_./?-]+@([0-9a-z-]+.)+[0-9a-z-]+$|', trim($_POST['email']))){
                  $emailError = 'メールアドレスが正しくありません';
			            $hasError = true;
                }else{
                  $email = trim($_POST['email']);
                }
                //お問い合わせ内容の入力なし
                if(trim($_POST['comments']) === '') {
                  $commentError = 'お問い合わせ内容が入力されていません';
                  $hasError = true;
                }else {
                  if(function_exists('stripslashes')) {
                    $comments = stripslashes(trim($_POST['comments']));
                  }else{
                    $comments = trim($_POST['comments']);
                  }
                }
                if($_POST['agree_privacy'] === ''){
                  $agreeError = '&#147;プライバシーポリシーに同意する&#148;にチェックをお願いいたします。';
                  $hasError = true;
                }else{
                  $agree = 'プライバシーポリシー('.get_option('home').'/privacy-policy)に同意しました。';
                }
                //エラーなしの場合、メール送信
                if(!isset($hasError)) {
                  mb_language("japanese");
			            mb_internal_encoding("UTF-8");
			            $emailTo = get_option('admin_email');
			            $subject = 'お問い合わせ';
                  $body = "
下記の通りお問い合わせを受け付けました。 \r\n
\r\n
-------------------------------------------------\r\n
お名前: $name \r\n
メールアドレス: $email \r\n
お問い合わせ内容: $comments \r\n
$agree \r\n
-------------------------------------------------
";
			$title = get_bloginfo('name');
			$from = mb_encode_mimeheader("$title"."のお問い合わせ","UTF-8");
			$headers = 'From: '.$from.' <'.$email.'>';
			mb_send_mail($emailTo, $subject, $body, $headers);
			
			
			//自動返信用
			$subject = 'お問い合わせ受付のお知らせ';
			$from = mb_encode_mimeheader("$title","UTF-8");
			$headers2 = 'From: '.$from.' <'.$emailTo.'>';
			$body = "
$name 様 \r\n
$title にお問い合わせありがとうございます。\r\n
改めて担当者よりご連絡をさせていただきますので、\r\n
今しばらくお待ちください。\r\n
\r\n
-------------------------------------------------\r\n
お名前：$name \r\n
メールアドレス：$email \r\n
お問い合わせ内容：$comments \r\n
$agree \r\n
-------------------------------------------------
";
                  mb_send_mail($email, $subject, $body, $headers2);
			            $emailSent = true;
                }
              }
            }//end お問い合わせ入力欄自動返信メール設定
endif;?>
<?php
global $wp_query;
$post_obj = $wp_query->get_queried_object();
$slug = $post_obj->post_name;  //投稿や固定ページのスラッグ
?>
<?php get_header(); ?>
<div class="wrapper--large">
  <main class="main--middle">
    <article class="archive">
      <?php if(!isset($emailSent) && $emailSent !== false)://お問い合わせ完了画面以外のタイトル?>    
        <h1 class="ttl-group__txt-en"><?php echo strtoupper($slug);?></h1>
        <p class="ttl-group__txt-ja--min" role="doc-subtitle"><?php the_title(); ?></p>      
      <?php endif;
       if (have_posts()) : while (have_posts()) : the_post(); ?>
        <section class="content__txt-outer--middle">
          <?php the_content();?> 
          <?php if(is_page(get_option('coloring_about_slug')))://aboutページだったら?>
          <h3 class="content__ttl3--punctuation">サイト名&nbsp;“<?php echo get_bloginfo( 'name' );?>”&nbsp;の由来</h3>
          <p class="content__ttl3-sub">当サイトの名前は、以下の3つが由来になっています。</p>
          <?php  
          $concept_isset_counter = count(coloring_about_concept()['ttl']);
            for($concept_isset_no = 0; $concept_isset_no < $concept_isset_counter; $concept_isset_no++):
              $concept_isset_noplus = $concept_isset_no+1;
              if($concept_isset_no === 0){
                echo '<ul class="desc__list">';
              }?>
                <li class="desc__list-item">
                  <div class="desc__list-ttl-outer">
                  <p class="desc__list-ttl">concept<span class="desc__list-ttl-no"><?php echo $concept_isset_noplus;?></span></p>
                  <?php if(coloring_about_concept()['img'][$concept_isset_no]):?>
                    <div class="desc__list-img-outer"><img src="<?php echo(coloring_about_concept()['img'][$concept_isset_no]);?>" class="desc__list-img" alt="コンセプト<?php echo $concept_isset_noplus;?>イメージ画像" width="80" height="65"/>
                    </div>
                    <?php endif;?>
                  </div> 
                  <div class="desc__list-lead-outer">
                  <div class="desc__list-lead">
                    <h4 class="desc__list-main-ttl"><?php echo coloring_about_concept()['ttl'][$concept_isset_no]?></h4>
                    <p class="desc__list-txt"><?php echo coloring_about_concept()['desc'][$concept_isset_no]?></p>
                  </div>
                  </div>
                </li>
              <?php if($concept_isset_noplus === $concept_isset_counter){
                echo '</ul>';
                if(get_option('coloring_about_lasttext')){
                  echo '<p class="desc__list-lasttxt">'.get_option('coloring_about_lasttext').'</p>'; 
                }
              }?>
            <?php endfor;?>
          <?php elseif(is_page(get_option('coloring_contact_slug'))):?>
            <?php if(isset($emailSent) && $emailSent === true)://お問い合わせ完了?>
              <h1 class="message__ttl"><?php echo get_option('coloring_contact_sentttl'); ?></h1>
              <div class="content__txt-outer--min">
              <p class="content__txt"><?php echo get_option('coloring_contact_senttxt'); ?></p></div>
              <?php $contactsent_isset_counter = count(coloring_contact_caution()['sent']);
              $contactsent_isset_counter_minus = $contactsent_isset_counter-1;
              for($contactsent_isset_no = 0; $contactsent_isset_no < $contactsent_isset_counter; $contactsent_isset_no++):
                if($contactsent_isset_no === 0){
                  echo '<ul class="caution__list">';
                }?>
                <li class="caution__list-item--thin"><?php echo coloring_contact_caution()['sent'][$contactsent_isset_no];?></li>
                <?php if($contactsent_isset_no === $contactsent_isset_counter_minus){
                echo '</ul>';
              }
              endfor;?>
              <div class="archive__button-outer--<?php if(is_mobile()){echo 'center';}else{echo 'right';}?>">
          <a href="/" class="archive__button-more--mid" role="button">TOPページへ</a>
        </div>
            <?php //end お問い合わせ完了?>  
            <?php else://お問い合わせページ?>
              <?php $contact_isset_counter = count(coloring_contact_caution()['caution']);
                for($contact_isset_no = 0; $contact_isset_no < $contact_isset_counter; $contact_isset_no++):?>
                  <p class="caution__list-item"><?php echo coloring_contact_caution()['caution'][$contact_isset_no];?></p>
                <?php endfor;?>   
                <form action="<?php the_permalink();?>" method="post">
                  <table class="contact__table">
                    <tr class="contact__input-list">
                      <th  class="contact__input-ttl">お名前<span class="contact__required">&#40;必須&#41;</span>
                      </th>
                      <td class="contact__input-outer">
                        <input type="text" name="contactName" value="<?php if(isset($_POST['contactName'])) echo $_POST['contactName'];?>" placeholder="お名前" class="contact__input--txt"/>
                        <?php if(isset($nameError)):?>
                          <span class="contact__error">
                            <?=$nameError;?>
                          </span>
                        <?php endif;?>
                      </td>
                    </tr>
                    <tr class="contact__input-list">
                      <th class="contact__input-ttl">メールアドレス<span class="contact__required">&#40;必須&#41;</span></th> 
                      <td class="contact__input-outer"><input type="text" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>" placeholder="メールアドレス" class="contact__input--txt"/>
                      <?php if(isset($emailError)):?>
                      <span class="contact__error">
                            <?=$emailError;?>
                      </span>         
                      <?php endif;?>        
                      </td>
                    </tr>
                    <tr class="contact__input-list">
                      <th class="contact__input-ttl">お問い合わせ内容<span class="contact__required">&#40;必須&#41;</span></th>
                      <td class="contact__input-outer">
                        <textarea name="comments" placeholder="お問い合わせ内容" class="contact__input--textarea"><?php if(isset($_POST['comments'])){
                          if(function_exists(('stripslashes'))){
                            echo stripslashes($_POST['comments']);
                          }else{
                            echo $_POST['comments'];
                          }
                        }?></textarea>
                        <?php if(isset($commentError)):?>
                          <span class="contact__error">
                                <?=$commentError;?>
                          </span>         
                        <?php endif;?> 
                      </td>
                    </tr>
                  </table>
                  <div class="contact__agree">
                  <input type="checkbox" name="agree_privacy" id="agree" value="プライバシーポリシーに同意する" autocomplete="off" required="required" />
                  <label for="agree" class="contact__agree-label"> <a href="<?php echo get_option('home');?>/privacy-policy/" class="content__link">プライバシーポリシー</a>に同意する</label>
                  <?php if(isset($agreeError)):?>
                    <span class="contact__error">
                          <?=$agreeError;?>
                    </span>         
                  <?php endif;?>
                  </div>
                  <div class="archive__button-outer--center">
                    <input type="hidden" name="submitted" value="true" /><button class="archive__button-submit--min" type="submit">送信する</button>
                  </div>
                </form>
              <?php endif;//end お問い合わせページ?> 
          <?php endif;?>
        </section>
      <?php endwhile; endif; ?>
    </article>
  </main>
  <!-- l-sidebar -->
    <?php get_sidebar(); ?>
  <!-- end sidebar -->
</div>  
<?php get_footer(); ?>