$(function(){
    
// Живой поиск в таблице navigard_users
$('.navigard_who').bind("change keyup input click", function() {
    if(this.value.length >= 2){
        $.ajax({
            type: 'post',
            url: "search.php",
            data: {'referal':this.value},
            response: 'text',
            success: function(data){
                $(".navigard_search_result").html(data).fadeIn(); // Отображаем результаты поиска
           }
       })
    }
})
    
$(".navigard_search_result").hover(function(){
    $(".navigard_who").blur(); // Убираем фокус при наведении на результаты
})
    
$(".navigard_search_result").on("click", "li", function(){
    s_user = $(this).text();
    $(".navigard_search_result").fadeOut(); // Скрываем результаты после выбора
})
})