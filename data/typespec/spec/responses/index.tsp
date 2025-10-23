import "./errors.tsp";
import "../models/common.tsp";

using TypeSpec.Http;

namespace Application;

model RecordReturn<T> {
  @statusCode statusCode: 200;
  ...Body<T>;
}

model CreatedReturn<T> {
  @statusCode statusCode: 201;
  ...Body<T>;
}

model UpdatedReturn<T> {
  @statusCode statusCode: 200;
  ...Body<T>;
}

model DeletedReturn {
  @statusCode statusCode: 204;
}

model SuccessReturn {
  @statusCode statusCode: 200;
  @body body: {
    message: string;
  };
}

alias IndexResponse<T> = HalCollection<T> | NotFoundError;
alias CreatedResponse<T> = CreatedReturn<HalRecord<T>> | ValidationError;
alias UpdatedResponse<T> = UpdatedReturn<HalRecord<T>> | ValidationError;
alias DeletedResponse = DeletedReturn;
alias RecordResponse<T> = RecordReturn<HalRecord<T>> | NotFoundError;
alias ReadResponse<T> = T | NotFoundError;
alias SuccessResponse = SuccessReturn | ValidationError | BadRequestError;
