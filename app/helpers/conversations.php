<?php

function getConversation($user_id, $conn)
{
  $sql = "SELECT * FROM conversations
            WHERE user_1=? OR user_2=?
            ORDER BY conversation_id DESC";

  $stmt = $conn->prepare($sql);
  $stmt->execute([$user_id, $user_id]);

  if ($stmt->rowCount() > 0) {
    $conversations = $stmt->fetchAll();

    $user_data = [];

    foreach ($conversations as $conversation) {
      if ($conversation['user_1'] == $user_id) {
        $sql2  = "SELECT *
            	          FROM users WHERE user_id=?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute([$conversation['user_2']]);
      } else {
        $sql2  = "SELECT *
            	          FROM users WHERE user_id=?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute([$conversation['user_1']]);
      }

      $allConversations = $stmt2->fetchAll();
      array_push($user_data, $allConversations[0]);
    }

    return $user_data;
  } else {
    $conversations = [];
    return $conversations;
  }
}
