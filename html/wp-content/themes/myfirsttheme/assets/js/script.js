const func = (() => {
  return {
    log: (str) => {
      console.log(str || 'hello');
    }
  }
})();
func.log();
