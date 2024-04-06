<?php

function calculateString($expression)
{
  $expression = preg_replace('/\s+/', '', $expression);

  if (!preg_match('/^[\d\s\(\)\+\-\*\/\.]+$/', $expression)) {
    return "Error: Неверные символы в выражении\n";
  }

  $postfixExpression = infixToPostfix($expression);

  return evaluatePostfix($postfixExpression);
}

function infixToPostfix($expression)
{
  $output = '';
  $stack = [];
  $operators = array('+' => 1, '-' => 1, '*' => 2, '/' => 2);

  $tokenizeRegex = '/(\d+|\+|-|\*|\/|\(|\))/';
  preg_match_all($tokenizeRegex, $expression, $tokens);



  $tokens = $tokens[0];

  foreach ($tokens as $token) {
    if (is_numeric($token)) {
      $output .= $token . ' ';
    } elseif ($token == '(') {
      array_push($stack, $token);
    } elseif ($token == ')') {
      while (count($stack) > 0 && end($stack) !== '(') {
        $output .= array_pop($stack) . ' ';
      }
      array_pop($stack);
    } elseif (array_key_exists($token, $operators)) {
      while (count($stack) > 0 && $operators[$token] <= $operators[end($stack)]) {
        $output .= array_pop($stack) . ' ';
      }
      array_push($stack, $token);
    }
  }

  while (count($stack) > 0) {
    $output .= array_pop($stack) . ' ';
  }

  return trim($output);
}


function evaluatePostfix($expression)
{
  $stack = [];
  foreach (explode(' ', $expression) as $token) {
    if (is_numeric($token)) {
      array_push($stack, floatval($token));
    } else {
      $operand2 = array_pop($stack);
      $operand1 = array_pop($stack);

      switch ($token) {
        case '+':
          array_push($stack, $operand1 + $operand2);
          break;
        case '-':
          array_push($stack, $operand1 - $operand2);
          break;
        case '*':
          array_push($stack, $operand1 * $operand2);
          break;
        case '/':
          array_push($stack, $operand1 / $operand2);
          break;
      }
    }
  }

  return array_pop($stack);
}

$userExpression = readline("Введите выражение: ");
$result = calculateString($userExpression);
echo "Результат: " . $result;
