<?php

require_once "api/v3/utils.php";

/**
 * CRM_RemoteAttachment_Page_RemoteAttachment defines an endpoint
 * (civicrm/ajax/remoteattachment) for uploading attachments.
 *
 * Upload attachments by sending a multi-part POST request including
 * the files to upload, as well as the following params:
 *   - entity_table: The table name of the entity to attach the file(s) to.
 *   - entity_id: The ID of the entity to attach the file(s) to.
 *   - description: An optional description of the attachment(s).
 *
 * The response is a JSON document containing a record for each attachment,
 * describing the success or failure of the upload.
 */
class CRM_RemoteAttachment_Page_RemoteAttachment {
  public static function run(): void {
    $result = self::createAttachments($_POST, $_FILES);

    CRM_Utils_JSON::output($result);
  }

  /**
   * @param array{entity_table: string, entity_id: int, description: string} $post
   * @param array{tmp_name: string, name: string, type: string, error: int}[] $files
   *
   * @return array
   */
  public static function createAttachments(array $post, array $files): array {
    $results = [];

    foreach ($files as $key => $file) {
      if ($file["error"]) {
        return civicrm_api3_create_error("Upload failed (code={$file["error"]})");
      }

      $results[$key] = self::createAttachment($post,$file);
    }

    return $results;
  }

  /**
   * @param array{entity_table: string, entity_id: int, description: string} $post
   * @param array{tmp_name: string, name: string, type: string, error: int} $file
   *
   * @throws \Exception
   * @return array
   */
  public static function createAttachment(array $post, array $file): array {
    $result = [];

    CRM_Core_Transaction::create(TRUE)
      ->run(function(CRM_Core_Transaction $transaction) use ($file, $post, &$result) {
        $result = civicrm_api3("Attachment", "create", [
          "name" => $file["name"],
          "mime_type" => $file["type"],
          "entity_table" => $post["entity_table"],
          "entity_id" => $post["entity_id"],
          "description" => $post["description"],
          "check_permissions" => 1,
          "content" => "",
        ]);

        if ($result["is_error"]) {
          return;
        }

        $moveResult = civicrm_api3("Attachment", "create", [
          "id" => $result["id"],
          "options.move-file" => $file["tmp_name"],
          "check_permissions" => 0,
        ]);

        if ($moveResult["is_error"]) {
          $result = $moveResult;

          $transaction->rollback();
        }
      });


    return $result;
  }
}
