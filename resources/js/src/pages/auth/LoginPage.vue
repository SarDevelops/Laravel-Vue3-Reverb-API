<script lang="ts" setup>
import { useVuelidate } from "@vuelidate/core";
import { required, email } from "@vuelidate/validators";
import { loginInput, useLoginUser } from "./actions/login";
import {onMounted,ref} from "vue";

const rules = {
    email: { required, email }, // Matches state.firstName
    password: { required }, // Matches state.lastName
};

const v$ = useVuelidate(rules, loginInput);
const { loading, login } = useLoginUser();
async function submitLogin() {
    const result = await v$.value.$validate();

    if (!result) return;

    await login();
    v$.value.$reset()

}



const inputs = ref([
    { type: 'text', label: 'Email', value: '' },
    { type: 'password', label: 'Password', value: '' }
]);

const splitLabel = (label) => {
    return label.split('');
};

const handleSubmit = () => {
    // Handle the form submission (e.g., validate and send data)
    console.log('Form submitted:', inputs.value);
};


</script>
<template>
    <div class="container">
        <h1>Please Login</h1>
        <form @submit.prevent="handleSubmit">
            <div class="form-control" v-for="(input, index) in inputs" :key="index">
                <input
                    type="text"
                    v-if="input.type === 'text'"
                    v-model="input.value"
                    required
                />
                <input
                    type="password"
                    v-if="input.type === 'password'"
                    v-model="input.value"
                    required
                />
                <label>
          <span
              v-for="(letter, idx) in splitLabel(input.label)"
              :key="idx"
              :style="{ transitionDelay: `${idx * 50}ms` }"
          >
            {{ letter }}
          </span>
                </label>
            </div>

            <button type="submit" class="btn">Login</button>
            <p class="text">Don't have an account? <a href="#">Register</a></p>
        </form>
    </div>
</template>


<style >
@import url('https://fonts.googleapis.com/css?family=Muli&display=swap');

* {
    box-sizing: border-box;
}

body {
    background-color: steelblue;
    color: #fff;
    font-family: 'Muli', sans-serif;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    overflow: hidden;
    margin: 0;
}

.container {
    background-color: rgba(0, 0, 0, 0.4);
    padding: 20px 40px;
    border-radius: 5px;
}

.container h1 {
    text-align: center;
    margin-bottom: 30px;
}

.container a {
    text-decoration: none;
    color: lightblue;
}

.btn {
    cursor: pointer;
    display: inline-block;
    width: 100%;
    background: lightblue;
    padding: 15px;
    font-family: inherit;
    font-size: 16px;
    border: 0;
    border-radius: 5px;
}

.btn:focus {
    outline: 0;
}

.btn:active {
    transform: scale(0.98);
}

.text {
    margin-top: 30px;
}

.form-control {
    position: relative;
    margin: 20px 0 40px;
    width: 300px;
}

.form-control input {
    background-color: transparent;
    border: 0;
    border-bottom: 2px #fff solid;
    display: block;
    width: 100%;
    padding: 15px 0;
    font-size: 18px;
    color: #fff;
}

.form-control input:focus,
.form-control input:valid {
    outline: 0;
    border-bottom-color: lightblue;
}

.form-control label {
    position: absolute;
    top: 15px;
    left: 0;
    pointer-events: none;
}

.form-control label span {
    display: inline-block;
    font-size: 18px;
    min-width: 5px;
    transition: 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.form-control input:focus + label span,
.form-control input:valid + label span {
    color: lightblue;
    transform: translateY(-30px);
}
</style>
