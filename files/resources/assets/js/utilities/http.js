import Vue from 'vue'
import VueResource from 'vue-resource'
import Cookies from 'js-cookie'

Vue.use(VueResource)

// Vue.http.interceptors.push((request, next) => {
//     let accessToken = localStorage.getItem('access_token')
//
//     if (accessToken != null) {
//         request.headers['Authorization'] = 'Bearer ' + accessToken
//     }
//
//     request.headers['X-XSRF-TOKEN'] = Cookies.get('XSRF-TOKEN')
//
//     next((response) => {
//         switch (response.status) {
//             case 401:
//                 console.log('session expired')
//                 localStorage.removeItem('access_token')
//                 localStorage.removeItem('refresh_token')
//                 break
//         }
//     })
// })
