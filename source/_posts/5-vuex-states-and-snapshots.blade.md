---
extends: _layouts.post
title: Vuex states and snapshots
author: Stuart Jones
date: 2019-01-21
section: post
---

My current (yet to be named) project for [Savvy Wombat](https://savvywombat.com.au) is using a Vue-based front end, with several interacting components. Each page might be made of several sub-forms, with each form being responsible for saving its values to the server. I also want to give users the ability to rollback each of these sub-forms independently if there are unsaved changes (simply refreshing the page would reset all forms).

I've turned to [Vuex](https://vuex.vuejs.org/) to help with managing the states of various properties that are editable and are used in multiple components. When casting about for solutions to my requirements, I found quite a few examples of how to reset to an initial state, but I wanted to move the goalposts a bit. After a bit of work I've got this simple snapshot system working.

## Starting point - a very simple app

<figure>
<img src="/assets/images/posts/5-vuex-states-and-snapshots--form.png" alt="form">
<figcaption>Simple form with submit and reset options</figcaption>
</figure>

Say you have this kind form, with options to _submit_ (to the server) and _reset_ (to the last saved state) the changes you've made. The value of the text input is also used in other parts of the application (in this case, simply for display - but it could also be used for calculations or other reactive behaviour).

```html
<template>
  <div>
    <h1>Project - {{ name }}</h1>
    <input type="text" v-model="name">

    <button @click="submit">submit</button>
    <button @click="reset">reset</button>
  </div>
 </template>
```

```javascript
<script>
export default {
    name: 'Project',

    computed: {
        name: {
            get() {
                return this.$store.state.project.name
            },
            set (value) {
                this.$store.commit('changeProjectName', value)
            }
        }
    },

    methods: {
        reset() {
            this.$store.commit('resetProjectName')
        },

        submit() {
            // send to server
            // create 'savepoint'
        }
    }
}
</script>
```

The minimal app and store code to enable this:

```javascript
import Vue from 'vue';
import Vuex from 'vuex';

// prep the Vuex store
Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        // initial state
        project: {
            name: 'Talk Talk'
        }
    },
    mutations: {
        // methods to effect changes on state
        changeProjectName(state, name) {
            state.project.name = name;
        },
        resetProjectName(state) {
            state.project.name = defaultState().project.name;
        }
    }
});

// import the components for this app
import Project from './project';

// provide the component and id to Vue to allow it to be used in the app
Vue.component('project', Project);

// start the app
const app = new Vue({
    el: '#app',
    // provide the store using the "store" option.
    // this will inject the store instance to all child components.
    store,
});
```

The problem here is that we can only reset to the initial state that was loaded when the page/app was last loaded. If we save any changes to the server, this initial state no longer matches what we have on the server, and resetting the form means that our 'reset' value will not match what is currently stored on the server..

## Adding the snapshot module

Vuex provides a way to use modules to help organise the store. Each module can create an independent store within the application which can be accessed through the root store. This means we can create an independently managed snapshot state while maintaining the default tree that reacts to live changes.

```javascript
// prep the Vuex store
Vue.use(Vuex);

const defaultState = () => {
    return {
        project: {
            name: 'Talk Talk'
        }
    }
};

const store = new Vuex.Store({
    state: defaultState(),
    modules: {
        snapshot: {
            state: defaultState()
        }
    },
    mutations: {
        // methods to effect changes on state
        changeProjectName(state, name) {
            state.project.name = name;
        },
        resetProjectName(state) {
            state.project.name = state.snapshot.project.name;
        },
        saveProjectName(state) {
            state.snapshot.project.name = state.project.name;
        }
    },
});
```

Changes to the project name field are still reflected in the store's state tree - making sure that other Vue components which use this property correctly react to the changes.

The property in the snapshot is updated only when the form's submit button is used. With a little bit more work, we could even have the snapshot update only after the server responds with success after the requested changes are made to the database there.