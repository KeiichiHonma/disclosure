<?php
//page
$lang['auth_setting_title'] = '登録情報';
$lang['auth_login_title'] = 'ログイン';
$lang['auth_register_title'] = '会員登録';
$lang['auth_send_again_title'] = 'メールの再送';
$lang['auth_register_complete_title'] = '会員登録完了';
$lang['auth_reset_password_title'] = 'パスワードの再発行';
$lang['auth_forgot_password_title'] = 'パスワードの再発行';
$lang['auth_forgot_password_complete_title'] = 'パスワードの再発行完了';
$lang['auth_change_password_title'] = 'パスワードの変更';
$lang['auth_change_email_title'] = 'メールアドレスの変更';
$lang['auth_reset_email_title'] = 'メールアドレスの再発行';
$lang['auth_unregister_title'] = '会員退会';

//変更
$lang['auth_setting_edit_title'] = '会員情報変更';
$lang['auth_email_edit_title'] = 'メールアドレス変更';
$lang['auth_password_edit_title'] = 'パスワード変更';

// Errors
$lang['auth_incorrect_password'] = 'パスワードが正しくありません';
$lang['auth_incorrect_login'] = '不正なログインです';

//$lang['auth_incorrect_email_or_username'] = 'ユーザー名もメールアドレスも登録されていません';
$lang['auth_incorrect_email_or_username'] = '入力されたメールアドレスは登録されていません';
$lang['auth_email_in_use'] = 'メールアドレスはすでに使われています。他のメールアドレスをお使いください。';
$lang['auth_username_in_use'] = 'ユーザー名はすでに使われています。他のユーザー名をお使いください。';
$lang['auth_current_email'] = 'これが現在お使いのメールアドレスです';
$lang['auth_incorrect_captcha'] = '確認コードが提示したイメージと異なっています。';
$lang['auth_captcha_expired'] = '確認コードの有効時間が過ぎました。もう一度、お願いします。';

// Notifications
$lang['auth_message_logged_out'] = 'ログアウトしました。';
$lang['auth_message_registration_disabled'] = '登録はできません。';
$lang['auth_message_registration_completed_1'] = '<p>本登録用のURLが記載されたメールが送信されますので、登録したメールアドレスをご確認ください。</p><p><em>[メールが届かない場合は、恐れ入りますが迷惑メールを一度ご確認ください。]</em></p>';
$lang['auth_message_registration_completed_2'] = '登録しました。';
$lang['auth_message_activation_email_sent'] = '会員アカウントを有効にするためのメールを %s に送りました。会員アカウントを有効化するためにご確認ください。';
$lang['auth_message_activation_completed'] = '本登録が完了しました。';
$lang['auth_message_activation_failed'] = 'アクティベーション・コードが違っているか、有効な時間を過ぎています。';
$lang['auth_message_password_changed'] = 'パスワードが変更されました。';
$lang['auth_message_new_password_sent'] = '<p>メールに記載されたリンクからパスワードの再設定をお願いします。</p><p><em>[現時点ではまだパスワードは変更されていません。]</em></p>';
$lang['auth_message_new_password_activated'] = 'パスワードが変更されました。';
$lang['auth_message_new_password_failed'] = "アクティベーション・キーが正しくないか、有効な時間を過ぎています。\nメールの内容をもう一度ご確認ください。";
$lang['auth_message_new_email_sent'] = "確認メールを %s に送りました。\n変更処理を完了させるため、内容を確認してください。\n【メールが届かない場合は、恐れ入りますが迷惑メールを一度ご確認ください。】";
$lang['auth_message_new_email_activated'] = 'メールアドレスを変更しました。';
$lang['auth_message_new_email_failed'] = "アクティベーション・キーが正しくないか有効な時間を過ぎています。\nメールの内容をもう一度ご確認ください。";
$lang['auth_message_banned'] = '非アクティベートしました。';
$lang['auth_message_unregistered'] = '会員アカウントを削除しました...';
$lang['auth_message_password_more_than'] = '%s文字以上%s文字以内の半角英数字';
$lang['auth_message_push_registration'] = '会員登録がまだお済みでない方はこちらからご登録をお願いします。';
$lang['auth_reset_password_message'] = "ログインパスワードをお忘れの場合は、このページから新しいパスワードを再発行できます。\nメールアドレスを入力して「パスワードを再発行する」ボタンを押してください。\nパスワード変更ページのURLが記載されたメールを送信します。";
$lang['auth_message_send_mail'] = 'ご登録いただいたメールアドレスにメールを送信しました。';
$lang['auth_message_read_rule'] = 'Balloooooon!の利用規約を必ずお読みください。';
$lang['auth_message_register_complete'] = '会員の仮登録が完了しました。';
$lang['auth_message_reset_password'] = "新しいパスワードを入力して「パスワードを再発行する」ボタンを押してください。\n忘れないように変更後のパスワードを記憶しておいてください。";


// Email subjects
$lang['auth_subject_welcome'] = '【%s】登録完了メール';
$lang['auth_subject_activate'] = '【%s】会員登録手続きのご案内';
$lang['auth_subject_forgot_password'] = '【%s】パスワードの再発行依頼を受付ました';
$lang['auth_subject_reset_password'] = '【%s】パスワード再発行のお知らせ';
$lang['auth_subject_change_email'] = '【%s】登録メールアドレスの変更を受付ました';

// others
$lang['Email or login'] = 'メールアドレスまたはユーザ名';
$lang['Email'] = 'メールアドレス';
$lang['Login'] = 'ユーザ名';
$lang['Password'] = 'パスワード';
$lang['Remember me'] = '次回から入力を省略';
$lang['Forgot password'] = 'パスワードを忘れた場合はこちら';
$lang['Let me in'] = 'ログイン';
$lang['Username'] = '名前（ローマ字）';
$lang['Email Address'] = 'メールアドレス';
$lang['Confirm Password'] = 'パスワード(再入力)';
$lang['Confirmation Code'] = 'CATPCHAコードの確認';
$lang['Profile Image'] = 'プロフィール画像';
$lang['Biography'] = '自己紹介';
$lang['Website'] = 'URL';
$lang['Email receive setting'] = 'Balloooooon!からのお知らせを受け取る';
$lang['Source'] = '何を見てBalloooooon!をお知りになりましたか?';
$lang['Language'] = '言語';
$lang['Request job'] = 'お仕事の依頼';
$lang['Job'] = '職業';
$lang['Address1'] = '住所';
$lang['Address2'] = '住所2';
$lang['Zip'] = 'ZIP';
$lang['Phone'] = '電話番号';
$lang['Wish job'] = '希望するお仕事(複数可)';
$lang['Self PR'] = '自己PR<br>(過去実績や得意分野など)';
$lang['Sex'] = '性別';
$lang['Register Birthday'] = '生年月日(非公開)';
$lang['Birthday'] = '生年月日';
$lang['Register'] = '規約に同意して登録する';
$lang['Register new'] = '会員登録する';
$lang['Enter the code exactly as it appears:'] = '表示された文字列を入力してください';
$lang['Get a new password'] = 'パスワードを再発行する';
$lang['auth_old_password'] = '古いパスワード';
$lang['auth_new_password'] = '新しいパスワード';
$lang['auth_new_password_confirm'] = '新しいパスワードの確認';
$lang['auth_change_password'] = 'パスワードの変更';
$lang['auth_url_is_invalid'] = 'URLが不正です';
$lang['Birthday Year'] = '生まれた年';
$lang['btn_login'] = 'ログインする';
$lang['auto_login'] = '次回から自動ログイン';

/* End of file tank_auth_lang.php */
/* Location: ./application/language/japanese/tank_auth_lang.php */