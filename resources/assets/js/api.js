import axios from 'axios';

const URL_MATCHES = '/api/match',
      URL_MATCH = '/api/match/',
      URL_MOVE = '/api/match/',
      URL_CREATE = '/api/match',
      URL_DELETE = '/api/match/';

export default {
    matches: () => axios.get(URL_MATCHES),
    match: (id) =>  axios.get(URL_MATCH + id),
    move: ({id, position}) => axios.put(URL_MOVE + id,  position),
    create: () => axios.post(URL_CREATE),
    destroy: (id) => axios.delete(URL_DELETE + id),
}
