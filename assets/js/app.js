import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './App'

import Home from './views/Home'
import NotFound from './views/NotFound'

Vue.use(VueRouter)

var router = new VueRouter({
    saveScrollPosition: true,
    history: true,
    hashbang: false
})

router.map({
    '/': {
        component: Home,
        name: 'home'
    },
    '*': {
        component: NotFound
    }
})

window.addEventListener('load', function () {
    router.start(App, '#app')
})
