//スクロールすると上部に固定させるための設定
window.addEventListener('scroll', () => {
  const header = document.querySelector('header');
  const headerH = header.offsetHeight;
  const scroll = window.pageYOffset;
  if (scroll >= headerH){//headerの高さ以上になったら
    header.classList.add('fixed');
  }else{
    header.classList.remove('fixed');
  }
});
//ハンバーガーメニュー
document.addEventListener("DOMContentLoaded", function(){
const headerMenu = document.getElementById('header__menu');
const menu = document.getElementById('menu');
//クリック箇所
headerMenu.addEventListener('click', () => {
  const name = headerMenu.getAttribute('aria-expanded');//aria-expanded取得
  menu.classList.toggle('open');
  if(name === 'false'){
    headerMenu.setAttribute('aria-expanded','true');
  }else{
    headerMenu.setAttribute('aria-expanded','false');
  }
});
}, false);