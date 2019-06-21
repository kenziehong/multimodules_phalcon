// In the future, we will likely need to display our tweets in many different sections of our site. 
// To make this task easier and less prone to errors, we’ve created a tweet component to be used throughout the site.

// You’ll notice that the username in the template is still hard-coded as CoderInTraining. 
//Let’s change this so that the author can also be provided as a prop.
//First, add 'author' to the props array 
//so that our component can accept the username of the tweet’s author as a prop.
const Tweet = Vue.component('tweet', {
    props: ['message', 'author'],
    template: '<div class="tweet"><h3>{{ author }}</h3><p>{{ message }}</p></div>'
   });