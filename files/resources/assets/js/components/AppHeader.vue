<template>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navigation" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand"  v-link="{ name: 'home' }"><img src="/static/logo.png"/></a>
            </div>

            <div class="collapse navbar-collapse" id="navigation">

                <div class="navbar-right hidden-xs"  v-if="isGuest">
                    <a v-link="{ name: 'login' }" class="btn btn-primary navbar-btn">Login</a>
                    <a v-link="{ name: 'register' }" class="btn btn-primary navbar-btn">Register</a>
                </div>

                <ul class="nav navbar-nav navbar-right" v-if="isLoggedIn">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            {{ user.username }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a v-link="{ name: 'account' }">My Account</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#" @click="logout">Log Out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
    import { isLoggedIn, isGuest, getUser } from '../state/getters'
    import { logoutUser } from '../state/actions'

    export default {
        vuex: {
            getters: {
                isLoggedIn,
                isGuest,
                user: getUser
            },
            actions: {
                logoutUser
            }
        },
        methods: {
            logout () {
                this.logoutUser()
                this.$router.go({name: 'home'})
            }
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
