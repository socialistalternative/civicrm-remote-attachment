# CiviCRM Remote Attachment

Upload attachments to CiviCRM from remote systems.

![Screenshot](/images/screenshot.png)

This extension provides an endpoint (`civicrm/ajax/remoteattachment`) to upload attachments for a specified entity.

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

- PHP 8+
- CiviCRM 5.69+

## Installation

Install using [cv](https://github.com/civicrm/cv):

```sh
cd <extension-dir>
cv dl remoteattachment@https://github.com/socialistalternative/civicrm-remote-attachment/archive/main.zip
```

Clone with Git:

```sh
cd <extension-dir>
git clone https://github.com/socialistalternative/civicrm-remote-attachment.git remoteattachment
cv en remoteattachment
```

## Getting Started

To upload attachments, make a multi-part POST request to `civicrm/ajax/remoteattachment`.

You must provide `entity_table` and `entity_id`, and optionally provide `description`.

Multiple files can be included in the request, and will be uploaded as separate attachments.

Requests can be authenticated with methods [described in the developer guide](https://docs.civicrm.org/dev/en/latest/framework/authx/), such as a JWT or API Key.

### Examples

#### cURL

```sh
curl --request POST \
  --url https://example.com/civicrm/ajax/remoteattachment \
  --header 'X-Civi-Auth: Bearer <jwt or api key>' \
  --form entity_table=civicrm_contact \
  --form entity_id=2 \
  --form 'description=Example upload' \
  --form file=@example.jpg
```

#### JavaScript/TypeScript

```ts
async function uploadAttachment({
  entityTable,
  entityId,
  description,
  file
}: {
  entityTable: string;
  entityId: string;
  description?: string;
  file: File;
}) {
  const form = new FormData();

  form.append("entity_table", entityTable);
  form.append("entity_id", entityId);
  form.append("file", file);

  if (description) {
    form.append("description", description);
  }

  const response = await fetch(
    "https://example.com/civicrm/ajax/remoteattachment",
    {
      method: "POST",
      headers: { "X-Civi-Auth": "Bearer <jwt or api key>" },
      body: formData
    }
  );

  return response.json();
}
```
