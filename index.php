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
          // Fungsi untuk menghitung
          function safeEval($expression)
          {
            // Membersihkan ekspresi masukan
            $expression = preg_replace('/[^0-9\+\-\*\/\.\(\)]/', '', $expression);

            // Mengecek pola pembagian dengan nol
            if (preg_match('/\/0+(\.0*)?($|[^0-9\.])/', $expression)) {
              return "Undefined";
            }

            // Pencegakan pemeriksaan sintaks dasar
            if (preg_match('/^[\/\*\+]/', $expression) || preg_match('/[\+\-\*\/]{2,}/', $expression)) {
              return "Syntax Error";
            }

            // Mengevaluasi ekspresi menggunakan eval
            try {
              $result = eval('return ' . $expression . ';');
              if ($result === FALSE) {
                throw new Exception("Invalid expression");
              }
              return $result;
            } catch (Exception $e) {
              return "Error: " . $e->getMessage();
            }
          }

          // Mengecek apakah formulir telah disubmit
          if (isset($_POST['submit']) && isset($_POST['display'])) {
            $expression = $_POST['display'];
            $result = safeEval($expression);
            echo "<input type='text' class='display-calculation' value='" . htmlspecialchars($result) . "' readonly>";
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
          <input type="submit" value="=" name="submit" class="operator" onclick="calculateValidation()">
        </div>
      </form>
    </div>
    <div>
      <p id="tagname">Created by Mohammad Raya Satriatama (4B) </p>
    </div>
  </div>
  <script>
    // Event listener untuk membatasi karakter-karakter yang dapat dimasukkan pada tampilan kalkulator
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

    // Fungsi untuk menambah karakter pada tampilan kalkulator
    function appendToDisplay(value) {
      var display = document.querySelector(".display-calculation");
      var firstChar = display.value.slice(0);
      var lastChar = display.value.slice(-1);

      // Memeriksa apakah karakter yang dimasukkan valid tanpa ada double operator
      if ((value === '+' || value === '-' || value === '*' || value === '/' || value === '.') &&
        (lastChar === '+' || lastChar === '-' || lastChar === '*' || lastChar === '/' || lastChar === '.')) {
        return;
      }

      // Pencegahan double nol pada nilai nol
      if ((value === '0' || value === '00') && firstChar === '0') {
        return;
      }

      // Memeriksa apakah karakter pertama adalah 0 dan karakter selanjutnya bukan operator
      if ((value !== '0' && value != '+' && value !== '-' && value !== '*' && value !== '/' && value !== '.') && firstChar === '0') {
        display.value = value;
        return;
      }

      // Memeriksa apakah titik desimal valid
      if (value === '.' && display.value === '') {
        return;
      }

      // Pencegahan double koma
      if (value === '.' && /\d+\.\d*$/.test(display.value)) {
        return;
      }

      // Mengoptimalkan tampilan dengan menghilangkan nol yang tidak perlu
      var optimizedValue = display.value.replace(/(\D|^)0+(\d+)/, '$1$2');

      display.value += value;
    }

    // Fungsi untuk membersihkan tampilan kalkulator
    function clearDisplay() {
      document.querySelector(".display-calculation").value = '0';
    }

    // Fungsi untuk menghapus karakter terakhir dari tampilan kalkulator
    function deleteLastChar() {
      var display = document.querySelector(".display-calculation");
      if (display.value.length === 1) {
        display.value = '0';
      } else if (display.value != '0') {
        display.value = display.value.slice(0, -1);
      }
    }

    // Fungsi untuk menghitung persentase dari nilai terakhir pada tampilan kalkulator
    function calculatePercentage() {
      var display = document.querySelector(".display-calculation");
      var expression = display.value;
      var lastChar = display.value.slice(-1);

      // Pencegahan perhitungan ketika karakter terakhir adalah operator
      if (lastChar === '+' || lastChar === '-' || lastChar === '*' || lastChar === '/' || lastChar === '.') {
        return;
      }

      // Perhitungan dengan mengambil nilai terakhir dari tampilan
      var numbers = expression.split(/[\+\-\*\/]/);
      var lastNumber = parseFloat(numbers[numbers.length - 1]);
      var percentage = lastNumber / 100;

      display.value = expression.substring(0, expression.lastIndexOf(lastNumber)) + percentage;
    }

    // Fungsi untuk menghitung hasil dari ekspresi matematika pada tampilan kalkulator
    function calculateValidation() {
      var display = document.querySelector(".display-calculation");
      var expression = display.value;
      var lastChar = expression.slice(-1);

      // Penambahan nilai nol setelah operator jika user tidak menginputkan nilai setelah operator
      if (lastChar === '+' || lastChar === '-' || lastChar === '*' || lastChar === '/') {
        expression += '0';
      }

      // Pencegahan error tak diketahui sebelum masuk server-side
      var result;
      try {
        result = eval(expression);
      } catch (error) {
        result = "Error";
      }
      display.value = expression;
    }
  </script>
</body>

</html>