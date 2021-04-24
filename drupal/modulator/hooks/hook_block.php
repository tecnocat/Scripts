/**
 * Implements hook_block() - TOKEN_USUARIO
 */
function TOKEN_HOOK_block($op = 'list', $delta = 0, $edit = array()) {

  $block = array();

  switch ($op) {

    case 'list':

      // When you declare a new block copy and paste this sentence
      // Block info ALWAYS must be contain 'VSF:' prefix to identify
      //$block['block_name']['info'] = t('VSF: Block name');
      break;

    case 'view':

      switch ($delta) {

        case 'block_name':
          // When you construct a new block use the name of the block in their
          // functions to identify in the code, use only one sentence to do this
          // You can copy this code for each any block you want to construct
          //$block['subject'] = t('Block name');
          //$block['content'] = _TOKEN_HOOK_block_BLOCK-NAME_content();
          break;
      }
      break;
  }

  return $block;
}