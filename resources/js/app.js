import Vue from 'vue'

Vue.config.productionTip = false
Vue.config.devtools = false

window.Vue = Vue

Vue.component('team', require('@/Pages/Team/Index').default)

new Vue({
  el: '#app',
  mounted() {
    let menuOpen = document.getElementById('mobile-open')
    let menuClose = document.getElementById('mobile-close')
    let menu = document.getElementById('mobile-menu')
    let submenuTriggers = document.querySelectorAll('[data-id]')
    let submenus = document.querySelectorAll('[data-submenu]')

    menuOpen.addEventListener('click', function (e) {
      menu.classList.remove('-translate-x-full')
    })

    menuClose.addEventListener('click', function (e) {
      menu.classList.add('-translate-x-full')
    })

    submenuTriggers.forEach((t) => {
      t.addEventListener('click', function (e) {
        submenus.forEach((s) => {
          if (t.dataset.id == s.dataset.submenu) {
            s.classList.toggle('grid')
            s.classList.toggle('hidden')
          }
        })
      })
    })
  },
})
