//1. create new Vue app
const app = new Vue({
    //2. attach Vue app to the HLML element
    el: '#app',

    //3.  added the data property and some pieces of dynamic data 
    //to our Vue app’s options object to get things kicked off
    data: {
        username: 'CoderInTraining',
        newTweet: '',
        tweets: [
        'Started learning to code today. Wish me luck!', 
        'Okay, I learned HTML, CSS, and JavaScript. But, how do I combine them together?? Send help.', 
        'Today I start learning Vue. I got this.'
        ],
        bio: 'Excited future software engineer.',
    }
    
});