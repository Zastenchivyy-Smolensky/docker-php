const token = document.querySelector('.container').dataset.token;

async function sendData(name, message) {
  let resultMessage = '';
  await fetch('?action=post', {
    method: 'POST',
    body: new URLSearchParams({
      name: name,
      message: message,
      token: token
    }),
  }).then(() => {
    resultMessage = '登録しました'
  }).catch(() => {
    resultMessage = '失敗しました';
  });
  return resultMessage;
}

async function getCurrentMessage() {
  const res = await fetch('?action=get_current_message');
  const currentMessage = res.json();
  return currentMessage;
}

async function postMessage(name, message) {
  const resultMessage = await sendData(name, message);
  const currentMessage = await getCurrentMessage();
  addMessage(currentMessage);
  return resultMessage;
}


function deleteMessage(id){
    fetch("?action=delete",{
        method: "POST",
        body: new URLSearchParams({
            id:  id,
            token: token
        })
    })
}
function changeImportance(id){
    fetch("?action=change_importance",{
        method: "POST",
        body: new URLSearchParams({
            id: id,
            token: token
        })
    })
}

function canPost(name, message) {
    let canPost = true;
    const alertName = document.getElementById('alert-name');
    const alertMessge = document.getElementById('alert-message');
    alertName.style.display = 'none';
    alertMessge.style.display = 'none';
    if (name.length > 20) {
      alertName.style.display = 'inline';
      canPost = false;
    }
    if (message.length > 140) {
      alertMessge.style.display = 'inline';
      canPost = false;
    }
    return canPost;
  }
  
  async function addMessage(currentMessage) {
    const id = currentMessage.id;
    const name = currentMessage.name;
    const time = currentMessage.time;
    const message = currentMessage.message;
    const liNode = document.createElement('li');
    liNode.classList.add('message');
    liNode.dataset.id = id;
    const headerNode = document.createElement('div');
    headerNode.classList.add('header');
    const nameNode = document.createElement('div');
    nameNode.textContent = name;
    nameNode.classList.add('name');
    const timeNode = document.createElement('div');
    timeNode.textContent = time;
    timeNode.classList.add('time');
    const messageNode = document.createElement('div');
    messageNode.textContent = message;
    messageNode.classList.add('message');
    const deleteNode = document.createElement('span');
    deleteNode.classList.add('delete');
    const deleteIconNode = document.createElement('i');
    deleteIconNode.classList.add('bi');
    deleteIconNode.classList.add('bi-x');
    const importanceNode = document.createElement('span');
    importanceNode.classList.add('importance');
    importanceNode.classList.add('low');
    const importanceIconNode = document.createElement('i');
    importanceIconNode.classList.add('bi');
    importanceIconNode.classList.add('bi-bookmark-fill');
    liNode.appendChild(headerNode);
    headerNode.append(nameNode);
    headerNode.append(timeNode);
    liNode.append(messageNode);
    liNode.append(deleteNode);
    deleteNode.append(deleteIconNode);
    importanceNode.appendChild(importanceIconNode);
    liNode.appendChild(importanceNode);
    const ulNode = document.querySelector('ul');
    ulNode.prepend(liNode);
  }
  
  document.querySelector('form').addEventListener('submit', async (e) => {
    e.preventDefault();
    let name = document.querySelector('[name="name"]').value;
    let message = document.querySelector('[name="message"]').value;
    if (canPost(name, message)) {
      const resultMessage = await postMessage(name, message);
      const popup = document.querySelector('.popup');
      popup.firstElementChild.textContent = resultMessage;
      popup.style.display = 'block';
      document.querySelectorAll('form > .label > span').forEach((span) => {
        span.style.display = 'none';
      })
      document.querySelector('[name="name"]').value = '';
      document.querySelector('[name="message"]').value = '';
      deleteBtns = document.querySelectorAll('.delete');
      importanceBtns = document.querySelectorAll('.importance');
      clickDeleteBtn();
      clickImportanceBtn();
    }
  });
  
  document.querySelector('.close-btn').addEventListener('click', () => {
    document.querySelector('.popup').style.display = 'none';
  })
  

let deleteBtns = document.querySelectorAll('.delete');
function clickDeleteBtn() {
  deleteBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
      const id = btn.parentNode.dataset.id;
      deleteMessage(id);
      btn.parentNode.remove();
    });
  });
}
clickDeleteBtn();

let importanceBtns = document.querySelectorAll('.importance');
function clickImportanceBtn() {
  importanceBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
      const id = btn.parentNode.dataset.id;
      changeImportance(id);
      if (btn.classList.contains('low')) {
        btn.classList.remove('low');
        btn.classList.add('middle');
      } else if (btn.classList.contains('middle')) {
        btn.classList.remove('middle');
        btn.classList.add('high');
      } else if (btn.classList.contains('high')) {
        btn.classList.remove('high');
        btn.classList.add('low');
      }
    });
  });
}
clickImportanceBtn();