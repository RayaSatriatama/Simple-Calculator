<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calculator</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <div class="container">
    <div class="calculator">
      <form method="post">
        <div class="display">
          <input type="text" name="display" class="display-calculation" value="<?php echo isset($_POST['display']) ? htmlspecialchars($_POST['display']) : '0'; ?>">
          <?php
          if (isset($_POST['submit'])) {
            if (isset($_POST['display'])) {
              $calculation = $_POST['display'];
              if (strpos($calculation, '/0') !== false) {
                $result = "Undefined";
              } else {
                $result = eval('return ' . $calculation . ';');
              }
              echo "<input id=\"result\" class=\"display-calculation\" value=\"Result: $result\" readonly>";
            } else {
              echo "<p>No calculation found!</p>";
            }
          }

          ?>
        </div>
        <div>
          <input type="submit" value="AC" onclick="clearDisplay()" class="operator">
          <input type="button" value="DE" onclick="deleteLastChar()" class="operator">
          <input type="button" value="%" onclick="calculatePercentage()" class="operator">
          <input type="button" value="/" onclick="appendToDisplay('/')" class="operator">
        </div>
        <div>
          <input type="button" value="7" onclick="appendToDisplay('7')">
          <input type="button" value="8" onclick="appendToDisplay('8')">
          <input type="button" value="9" onclick="appendToDisplay('9')">
          <input type="button" value="*" onclick="appendToDisplay('*')" class="operator">
        </div>
        <div>
          <input type="button" value="4" onclick="appendToDisplay('4')">
          <input type="button" value="5" onclick="appendToDisplay('5')">
          <input type="button" value="6" onclick="appendToDisplay('6')">
          <input type="button" value="-" onclick="appendToDisplay('-')" class="operator">
        </div>
        <div>
          <input type="button" value="1" onclick="appendToDisplay('1')">
          <input type="button" value="2" onclick="appendToDisplay('2')">
          <input type="button" value="3" onclick="appendToDisplay('3')">
          <input type="button" value="+" onclick="appendToDisplay('+')" class="operator">
        </div>
        <div>
          <input type="button" value="00" onclick="appendToDisplay('00')">
          <input type="button" value="0" onclick="appendToDisplay('0')">
          <input type="button" value="." onclick="appendToDisplay('.')">
          <input type="submit" value="=" name="submit" class="operator" onclick="calculate()">
        </div>
      </form>
    </div>
    <div>
      <p id="tagname">Created by Mohammad Raya Satriatama (4B) </p>
    </div>
  </div>
  <script>
    document.querySelector(".display-calculation").addEventListener('input', function() {
      var display = this;
      var inputValue = display.value;

      inputValue = inputValue.replace(/[\+\-\*\/]{2,}/g, function(match) {
        return match[0];
      });

      inputValue = inputValue.replace(/\.{2,}/g, '.');
      inputValue = inputValue.replace(/(\d+\.\d*)\./g, '$1');

      display.value = inputValue;
    });

    function appendToDisplay(value) {
      var display = document.querySelector(".display-calculation");
      var firstChar = display.value.slice(0);
      var lastChar = display.value.slice(-1);

      if ((value === '+' || value === '-' || value === '*' || value === '/' || value === '.') &&
        (lastChar === '+' || lastChar === '-' || lastChar === '*' || lastChar === '/' || lastChar === '.')) {
        return;
      }

      if ((value === '0' || value === '00') && firstChar === '0') {
        return;
      }

      if ((value !== '0' && value != '+' && value !== '-' && value !== '*' && value !== '/' && value !== '.') && firstChar === '0') {
        display.value = value;
        return;
      }

      if (value === '.' && display.value === '') {
        return;
      }

      if (value === '.' && /\d+\.\d*$/.test(display.value)) {
        return;
      }

      var optimizedValue = display.value.replace(/(\D|^)0+(\d+)/, '$1$2');

      display.value += value;
    }

    function clearDisplay() {
      document.querySelector(".display-calculation").value = '0';
    }

    function deleteLastChar() {
      var display = document.querySelector(".display-calculation");
      if (display.value != '0') {
        display.value = display.value.slice(0, -1);
      }
    }

    function calculatePercentage() {
      var display = document.querySelector(".display-calculation");
      var expression = display.value;
      var lastChar = display.value.slice(-1);

      if (lastChar === '+' || lastChar === '-' || lastChar === '*' || lastChar === '/' || lastChar === '.') {
        return;
      }

      var numbers = expression.split(/[\+\-\*\/]/);
      var lastNumber = parseFloat(numbers[numbers.length - 1]);
      var percentage = lastNumber / 100;

      display.value = expression.substring(0, expression.lastIndexOf(lastNumber)) + percentage;
    }

    function calculate() {
      var display = document.querySelector(".display-calculation");
      var expression = display.value;

      // Add "0" if expression ends with an operator
      var lastChar = expression.slice(-1);
      if (lastChar === '+' || lastChar === '-' || lastChar === '*' || lastChar === '/') {
        expression += '0';
      }

      var result;
      try {
        // Use eval to calculate result
        result = eval(expression);
      } catch (error) {
        result = "Error";
      }
      display.value = expression;
    }
  </script>
</body>

</html>