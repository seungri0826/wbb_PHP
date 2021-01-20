# wbb_PHP
 NET 챌린지 시즌 7 왕밤빵 - /var/www/html 내 php 코드 (compare_box)

### 주요 코드 설명
- `compare_box_1.php`: [LINK](https://github.com/seungri0826/wbb_PHP/blob/master/www/html/compare_box_1.php)
  - Darknet을 `exec`하여 계속 실행
  - `.weights` 업로드는 처음에 한 번만 (시간 오래 걸리는 단계를 처음 한 번만 실행하도록 함)
  - 이어서 detection 진행
- `compare_box_2.php`: [LINK](https://github.com/seungri0826/wbb_PHP/blob/master/www/html/compare_box_2.php)
  - 라즈베리파이로부터 CCTV 이미지를 받아와 로컬에 저장 (계속 덮어쓰기하여 이미지 파일을 라즈베리파이 당 하나로 유지)
  - 도난 상황 감지 알고리즘 (star 알고리즘)
> 라즈베리파이가 처음 실행 시 `isFirst` 변수를 POST에 포함시켜 보내므로, Jetson에서는 `compare_box_2.php`에서 `isFirst == 1`일 때 Darknet을 실행하는 `compare_box_1.php`에게 `id`를 포함하여 localhost POST를 전송함. 왜냐하면 `compare_box_1.php`에서 라즈베리파이에서 보내오는 `id`값이 필요하기 때문임.
- `compare_box_3.php`: [LINK](https://github.com/seungri0826/wbb_PHP/blob/master/www/html/compare_box_3.php)
  - 실험용 코드
  - n초마다 다시 실행시켜주는 라즈베리파이 없이 수행한 실험이므로, `while(true)` 문 안에 `sleep(5);` 사용해서 5초 delay 부여하며 반복 실행
----------------
### 라즈베리파이 - Jetson 연결 시 필요한 절차
- 같은 핫스팟에 RPi랑 Jetson 연결
- RPi python 코드에서 IP 주소 Jetson의 사설 IP 주소로 바꾸기
- Jetson 방화벽 설정 restore 하기
- (맨 처음 한 번만) `sudo chown -R www-data:www-data /var/www`
