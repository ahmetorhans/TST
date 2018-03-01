
import { Notify } from 'quasar'


function notify(msg,err=false) {
  var icon = 'done';
  let color = 'positive';

  if (err===true){
    icon= 'error';
    color='negative';
  }
  
  return Notify.create({
    message: msg,
    position: 'top-right',
    color: color,
    timeout: 2000,
    icon : icon
  })
}
export default notify;