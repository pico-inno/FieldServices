
const debounce = (callback, wait = 400) => {
    let timeoutId = null;
    return function(...args){
        clearTimeout(timeoutId);
        const next = () => callback.apply(this, args);
        timeoutId = setTimeout(next, wait);
    }
}
