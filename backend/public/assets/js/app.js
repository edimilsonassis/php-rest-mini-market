
class Auth {
    async login(user, token) {
        localStorage.setItem('user', JSON.stringify(user));
        localStorage.setItem('token', token);
    }

    async logout() {
        localStorage.removeItem('user');
        localStorage.removeItem('token');

        this.checkAuth()
    }

    async getMe() {
        const user = localStorage.getItem('user');
        return JSON.parse(user);
    }

    async getToken() {
        return localStorage.getItem('token');
    }

    async checkAuth() {
        if (!await this.getMe()) {
            if (location.pathname != '/login') {
                return window.location.href = '/login';
            }
        } else {
            if (location.pathname == '/login') {
                return window.location.href = '/';
            }
        }
    }
}

class App {
    constructor() {
        this.auth = new Auth();

        this.auth.checkAuth();
    }
}

const app = new App();
