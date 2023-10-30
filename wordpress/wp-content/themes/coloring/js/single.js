function copyUrl() {
  const element = document.createElement('input');
  const txt = document.getElementById('copy-txt');
  element.value = location.href;
  document.body.appendChild(element);
  element.select();
  document.execCommand('copy');
  document.body.removeChild(element);
  txt.classList.add('copy-txt__fixed');
  setTimeout(function() {
    txt.classList.remove('copy-txt__fixed');
  }, 2000);
}
document.addEventListener("DOMContentLoaded", function(){
  //スムーススクロール
  const smoothScrollTrigger = document.querySelectorAll('a[href^="#"]');
  for (let i = 0; i < smoothScrollTrigger.length; i++){
    smoothScrollTrigger[i].addEventListener('click', (e) => {
      e.preventDefault();
      let href = smoothScrollTrigger[i].getAttribute('href');
      let targetElement = document.getElementById(href.replace('#', ''));
      const rect = targetElement.getBoundingClientRect().top;
      const offset = window.pageYOffset;
      const gap = document.getElementById('header').offsetHeight;
      const target = rect + offset - gap;
      window.scrollTo({
        top: target,
        behavior: 'smooth',
      });
    });
  }
  const images = document.querySelectorAll('a.content__gifopen');
  images.forEach((e) => {
    e.addEventListener('click', gifOpen);
  });
}, false); 
function gifOpen(){
  this.classList.toggle('gifopen');
  if(this.className !== 'content__gifopen gifopen'){
    src = this.parentNode.nextElementSibling.innerHTML;
    src = src.replace('<!--', '').replace('-->', '');
    this.parentNode.nextElementSibling.innerHTML=src;
    this.parentNode.nextElementSibling.style.display ='block';
  }else{
    this.parentNode.nextElementSibling.style.display ='none';
  }
}