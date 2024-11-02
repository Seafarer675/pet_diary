const slider = document.querySelector('.slider');

function activate(e) {
  const items = document.querySelectorAll('.item');
  e.target.matches('.next') && slider.append(items[0])      //將第一個內容放到陣列的最後一個 此時該內容的索引會是該陣列的最後一個
  e.target.matches('.prev') && slider.prepend(items[items.length-1]);   //將最後一個內容移到第一個 此時該內容的索引會是01
}
document.addEventListener('click',activate,false);


window.addEventListener('load', function() {
    const state = false
    const logoContainer = document.getElementById('logo_container');
    setTimeout(function() {
        logoContainer.classList.add('small_logo_container');
    }, 250);
});

$(document).ready(function(){
  // 初始页面加载时检查会话状态
  //checkSession();

  // 登录按钮点击事件

  // 登出按钮点击事件
  $("#Logout").click(function(){
      $.ajax({
          type: 'POST',
          url: 'http://localhost/final/logout.php',
          success: function(response){
              console.log(response) ;
          },
          error: function(xhr, status, error){
              console.error("An error occurred:", status, error);
          }
      });
  });


});