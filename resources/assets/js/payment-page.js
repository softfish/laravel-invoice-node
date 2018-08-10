var Vue = require('vue')

new Vue({
    el: '#payment-page',
    data: function() {
        return {
            maskon: false,
            currentTime: null,
            refreshTime: null,
            differentTime: null,
            interval: 1000,
        };
    },
    created: function() {
        this.currentTime = new Date().getTime();
        // Refresh the section every 10 mins
        this.refreshTime = this.currentTime + (10 * 60 * 1000);

        setInterval(() => {
            if (this.currentTime < this.refreshTime) {
                this.differentTime = this.refreshTime - this.currentTime;
                if (this.differentTime < (5*1000)) {
                    this.maskon = true;
                }
                this.currentTime += 1000; // reduce 1 sec
            } else {
                location.reload();
            }
        }, 1000);
    },
});