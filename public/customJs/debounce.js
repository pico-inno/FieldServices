
const debounce = (callback, wait = 500) => {
    let timeoutId = null;
    return function(...args){
        clearTimeout(timeoutId);
        const next = () => callback.apply(this, args);
        timeoutId = setTimeout(next, wait);
    }
}
