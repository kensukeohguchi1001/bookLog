<?php


function validate($review)
{
  $errors = [];
  // タイトルの入力確認review
  if(!strlen($review['title'])){
    $errors['title'] = '書籍名を入力してください';
  }elseif(strlen($review['title']) > 255){
    $errors['title'] = '書籍名は255文字以内で入力してください';
  }
  // 著者名の入力確認
  if(!strlen($review['author'])){
    $errors['author'] = '著者名を入力してください';
  }elseif(strlen($review['author']) > 255){
    $errors['author'] = '著者名は255文字以内で入力してください';
  }
  // 読書状況入力確認
$check_status = array("未読","読んでる","読了");
if(!in_array($review['status'],$check_status)){
  $errors['status'] = '未読,読んでる,読了のいずれかで答えてください';
}
  // 評価入力確認
  if($review['score'] < 1 || $review['score'] > 5) {
    $errors['score'] = '1〜5の数字を入力してください';
  }
  // 感想の入力確認
  if (!strlen($review['summary'])) {
    $errors['summary'] = '感想を入力してください';
  } elseif (strlen($review['summary']) > 1000) {
    $errors['summary'] = '感想は1000文字以内で入力してください';
  }

  return $errors;
}


// 接続から接続情報を返す
function dbConnect()
{
  // 接続
  $link = mysqli_connect('db', 'book_log', 'pass', 'book_log');

  if (!$link) {
    echo 'ERROR: データベースに接続できませんでした' . PHP_EOL;
    echo 'Debugging error:' . mysqli_connect_error() . PHP_EOL;
    exit;
  }
  return $link;
}

function createReview($link)
{

  echo '読書ログを登録してください' . PHP_EOL;
  echo '書籍名:';
  $review['title'] = trim(fgets(STDIN));
  echo '著者名:';
  $review['author'] = trim(fgets(STDIN));
  echo '読書状況(未読,読んでる,読了):';
  $review['status'] = trim(fgets(STDIN));
  echo '評価:';
  $review['score'] = (int) trim(fgets(STDIN));
  echo '感想:';
  $review['summary'] = trim(fgets(STDIN));

  $validated = validate($review);
  if (count($validated) > 0) {
      foreach($validated as $error) {
        echo $error . PHP_EOL;
      }
      return;
  }

  $sql = <<<EOL
INSERT INTO reviews(
  title,
  author,
  status,
  score,
  summary
) VALUES (
  '{$review['title']}',
  '{$review['author']}',
  '{$review['status']}',
  '{$review['score']}',
  '{$review['summary']}'
)
EOL;

  $result = mysqli_query($link, $sql);

  if ($result) {
    echo 'データが登録されました' . PHP_EOL;
  } else {
    echo 'データを登録できませんでした' . PHP_EOL;
    echo 'Debugging error :' . mysqli_error($link) . PHP_EOL;
  }
}

// データベースからデータを取得しlistReviewの中で使えるようにする
function listReview($link)
{

  $sql = "SELECT id,title,author,status,score,summary from reviews";
  $reviews = mysqli_query($link, $sql);
  echo '登録されている読書ログを表示します' . PHP_EOL;
  while($review = mysqli_fetch_assoc($reviews)){
    echo '書籍名：' . $review['title'] . PHP_EOL;
    echo '著者名：' . $review['author'] . PHP_EOL;
    echo '読書状況：' . $review['status'] . PHP_EOL;
    echo '評価：' . $review['score'] . PHP_EOL;
    echo '感想：' . $review['summary'] . PHP_EOL;
    echo '-------------' . PHP_EOL;
  }
}

$reviews = [];

$link = dbConnect();
while (true) {

  echo '1.読書ログを登録' . PHP_EOL;
  echo '2.読書ログを表示' . PHP_EOL;
  echo '3.アプリケーションを終了' . PHP_EOL;
  echo '番号を選択してください(1,2,9) :';
  $num = trim(fgets(STDIN));

  if ($num === '1') {
    createReview($link);
  }elseif ($num === '2'){
    listReview($link);
  }elseif ($num === '9'){;
    mysqli_close($link);
    echo 'データベースとの接続を切断しました' . PHP_EOL;
    break;
  }
}






// if($num === '1'){
// echo '読書ログを登録してください'. PHP_EOL;
// echo '書籍名:';
// $title = trim(fgets(STDIN));
// echo '著者名:';
// $person = trim(fgets(STDIN));
// echo '読書状況:';
// $status = trim(fgets(STDIN));
// echo '評価:';
// $score= trim(fgets(STDIN));
// echo '感想:';
// $summary = trim(fgets(STDIN));

// echo review'登 = [];録されました'. PHP_EOL;
// } elseif ($num === '2'){

// echo '登録されている読書ログを表示します' . PHP_EOL;
// echo '書籍名:' . $title . PHP_EOL;
// echo '著者名:' . $person . PHP_EOL;
// echo '読書状況:' . $status . PHP_EOL;
// echo '評価:' . $score. PHP_EOL;
// echo '感想:' . $summary . PHP_EOL;

// } elseif ($num === '9review') = [];{
//   // アプリケーションを終了する
// }










// echo '読書ログを表示します'. PHP_EOL;

// echo '書籍名:' . $title. PHP_EOL;
// echo '著者名:' . $person. PHP_EOL;
// echo '読書状況:' . $status. PHP_EOL;
// echo '評価:' . $review. PHP_EOL;
// echo '感想:' . $summary. PHP_EOL;review


// echo '書籍名:銀河鉄道の夜'. PHP_EOL;
// echo '著者名:宮沢賢治' . PHP_EOL;
// echo '読書状況:読了' . PHP_EOL;
// echo '評価:5' . PHP_EOL;
// echo '感想:本当の幸せとは何だろうと考えさせられる作品だった' . PHP_EOL;
