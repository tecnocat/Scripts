/* var_dump from PHP to Javascript ;-) */
function var_dump(data,addwhitespace,safety,level) {
  var rtrn = '';
  var dt,it,spaces = '';
  if (!level) { level = 1; }
  for (var i=0; i<level; i++) {
    spaces += '   ';
  }
  if (typeof(data) != 'object') {
    dt = data;
    if (typeof(data) == 'string') {
      if (addwhitespace == 'html') {
        dt = dt.replace(/&/g,'&amp;');
        dt = dt.replace(/>/g,'&gt;');
        dt = dt.replace(/</g,'&lt;');
      }
      dt = dt.replace(/\"/g,'\"');
      dt = '"' + dt + '"';
    }
    if (typeof(data) == 'function' && addwhitespace) {
      dt = new String(dt).replace(/\n/g,"\n" + spaces);
      if (addwhitespace == 'html') {
        dt = dt.replace(/&/g,'&amp;');
        dt = dt.replace(/>/g,'&gt;');
        dt = dt.replace(/</g,'&lt;');
      }
    }
    if (typeof(data) == 'undefined') {
      dt = 'undefined';
    }
    if (addwhitespace == 'html') {
      if (typeof(dt) != 'string') {
        dt = new String(dt);
      }
      dt = dt.replace(/ /g,"&nbsp;").replace(/\n/g,"<br>");
    }
    return dt;
  }
  for (var x in data) {
    if (safety && (level > safety)) {
      dt = '*RECURSION*';
    } else {
      try {
        dt = var_dump(data[x],addwhitespace,safety,level + 1);
      } catch (e) { continue; }
    }
    it = var_dump(x,addwhitespace,safety,level+1);
    rtrn += it + ':' + dt + ',';
    if (addwhitespace) {
      rtrn += '\n'+spaces;
    }
  }
  if (addwhitespace) {
    rtrn = '{\n' + spaces + rtrn.substr(0,rtrn.length - (2 + (level * 3))) + '\n' + spaces.substr(0, spaces.length - 3) + '}';
  } else {
    rtrn = '{' + rtrn.substr(0, rtrn.length - 1) + '}';
  }
    if (addwhitespace == 'html') {
    rtrn = rtrn.replace(/ /g,"&nbsp;").replace(/\n/g,"<br>");
  }
  return rtrn;
}