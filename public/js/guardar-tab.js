persistirTab = function () {
    window.localStorage.setItem('tab', currentTab);
}
var currentTab = '#tab1';
  // 
guardarTabActual = function(item){
    currentTab =  item.getAttribute('href');
}

asignarFuncionGuardado = function(){
    var tabs = document.querySelectorAll('[role="tab"]');
    tabs.forEach(element => {
        element.onclick = function (){guardarTabActual(element)};
    });
}
window.onload = () => {
    asignarFuncionGuardado();
    recuperarTab();
};
recuperarTab = function(){
    var tab = window.localStorage.getItem('tab') || '#tab1';
    window.localStorage.removeItem('tab');
    var tabId = tab.substring(1);
    document.getElementById(tabId).classList.add('active');
    document.querySelectorAll('[href="'+tab+'"]')[0].classList.add('active');    
}