using TypeSpec.Http;

namespace Application;

@error
model NotFoundError {
  @statusCode statusCode: 404;
  message: string;
}

@error
model BadRequestError {
  @statusCode statusCode: 400;
  message: string;
}

@error
model ValidationError {
  @statusCode statusCode: 422;
  message: string;
  errors: Record<string[]>;
}
