import { toast } from "sonner";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import Layout from "@/components/elements/layout";
import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import { Separator } from "@/components/ui/separator";
import { Textarea } from "@/components/ui/textarea";
import { Link } from "@inertiajs/inertia-react";
import axios from "axios";
import { ChevronLeft, Eye, Heart, MessageSquare } from "lucide-react";
import React, { useEffect, useState } from "react";
import { Skeleton } from "@/components/ui/skeleton";
import { z } from "zod";
import { cn } from "@/lib/utils";

const formSchema = z.object({
    content: z.string().min(1, "Komentar harus di isi."),
});

export default function Home({ id }) {
    const token = localStorage.getItem("token");
    const [data, setData] = useState(null);
    const [loading, setLoading] = useState(false);
    const [comment, setComment] = useState([]);
    async function getComment() {
        const response = await axios.get(`/api/v1/comment/${id}`, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        setComment(response.data.data);
    }
    async function fetchProfile() {
        setLoading(true);
        const response = await axios.get(`/api/v1/forum/${id}`, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        setData(response.data.data);
        setLoading(false);
    }
    useEffect(() => {
        getComment();
        fetchProfile();
    }, []);

    const {
        register,
        handleSubmit,
        formState: { errors },
        watch,
        reset,
    } = useForm({
        resolver: zodResolver(formSchema),
        defaultValues: {
            content: "",
        },
    });

    const submit = async (data) => {
        const { content } = data;
        const fData = new FormData();
        fData.append("content", content);

        await axios
            .post(
                `/api/v1/comment/${id}`,
                {
                    content: content,
                },
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                }
            )
            .then((res) => {
                toast.success(res.data.message);
                reset();
                getComment();
            })
            .catch((err) => {
                toast.error(err.response.data.message);
            });
    };

    return (
        <Layout>
            <div className="flex items-center justify-between gap-3 my-4">
                <Button variant="ghost" size="icon" asChild>
                    <Link href="/">
                        <ChevronLeft />
                    </Link>
                </Button>
                <h3 className="scroll-m-20 text-2xl font-semibold tracking-tight">
                    Detail Forum
                </h3>
                <span></span>
            </div>
            <div className="w-full flex flex-col gap-y-4">
                <Card className="w-full mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden group">
                    <div className="md:flex">
                        <div className="md:flex-shrink-0">
                            <span className="object-cover md:w-48 rounded-md bg-muted w-[192px] h-[192px]" />
                        </div>
                        <div className="p-8 w-full">
                            <div className="flex items-start justify-between">
                                <div className="flex items-start">
                                    {loading ? (
                                        <>
                                            <Skeleton className="size-[40px] rounded-full" />
                                            <div className="ml-4 flex flex-col gap-4">
                                                <Skeleton className="h-4 w-[250px]" />
                                                <Skeleton className="h-4 w-[200px]" />
                                            </div>
                                        </>
                                    ) : (
                                        <>
                                            <img
                                                alt="pp"
                                                className="rounded-full"
                                                height="40"
                                                src={data?.img_url}
                                                style={{
                                                    aspectRatio: "40/40",
                                                    objectFit: "cover",
                                                }}
                                                width="40"
                                            />
                                            <div className="ml-4">
                                                <div className="uppercase tracking-wide text-sm text-black dark:text-white font-semibold">
                                                    {data?.supervisor?.name}
                                                </div>
                                                <div className="text-gray-400 dark:text-gray-300">
                                                    {data?.supervisor?.label}
                                                </div>
                                            </div>
                                        </>
                                    )}
                                </div>
                            </div>
                            {loading ? (
                                <>
                                    <Skeleton className="h-4 w-full my-4" />
                                    <Skeleton className="h-36 w-full my-4" />
                                </>
                            ) : (
                                <div className="my-4 text-gray-500 dark:text-gray-300">
                                    <div
                                        dangerouslySetInnerHTML={{
                                            __html: data?.content,
                                        }}
                                    />
                                </div>
                            )}
                            <div className="flex mt-6 justify-between items-center">
                                <div className="flex space-x-4 text-gray-400 dark:text-gray-300">
                                    <div className="flex items-center text-red-500">
                                        <Eye className="size-5" />
                                        {loading ? (
                                            <Skeleton className="size-4 ml-1" />
                                        ) : (
                                            <span className="text-sm ml-1">
                                                {data?.view_count}
                                            </span>
                                        )}
                                    </div>
                                    <div className="flex items-center text-green-500">
                                        <Heart className="size-5" />
                                        {loading ? (
                                            <Skeleton className="size-4 ml-1" />
                                        ) : (
                                            <span className="text-sm ml-1 ">
                                                {data?.like_count}
                                            </span>
                                        )}
                                    </div>
                                    <div className="flex items-center text-blue-500">
                                        <MessageSquare className="size-5" />
                                        {loading ? (
                                            <Skeleton className="size-4 ml-1" />
                                        ) : (
                                            <span className="text-sm ml-1 ">
                                                {data?.comment_count}
                                            </span>
                                        )}
                                    </div>
                                </div>
                                {loading ? (
                                    <Skeleton className="w-40 h-4" />
                                ) : (
                                    <div className="text-gray-400 dark:text-gray-300">
                                        {new Date(
                                            data?.created_at
                                        ).toLocaleString("id-ID")}
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </Card>
                <Card className="w-full mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden group">
                    <div className="md:flex">
                        <div className="p-8 w-full">
                            {token ? (
                                <form onSubmit={handleSubmit(submit)}>
                                    <div className="w-full flex items-start justify-between mb-4">
                                        <h3 className="scroll-m-20 text-2xl font-semibold tracking-tight">
                                            Tanggapan
                                        </h3>
                                        <Button>Kirim</Button>
                                    </div>
                                    <Textarea
                                        className={cn(
                                            "my-4",
                                            errors.content &&
                                                "border-destructive"
                                        )}
                                        placeholder="..."
                                        {...register("content")}
                                        value={watch("content")}
                                    />
                                    {errors.content && (
                                        <p className="text-red-500">
                                            {errors.content?.message}
                                        </p>
                                    )}
                                </form>
                            ) : (
                                <>
                                    <h3 className="scroll-m-20 text-2xl font-semibold tracking-tight">
                                        Tanggapan
                                    </h3>
                                    <Separator className="my-6" />
                                </>
                            )}
                            {comment.length > 0 ? (
                                comment.map((item) => (
                                    <div key={item.id}>
                                        <div className="flex items-start justify-between my-4">
                                            <div className="flex items-start">
                                                <img
                                                    alt="pp"
                                                    className="rounded-full"
                                                    height="40"
                                                    style={{
                                                        aspectRatio: "40/40",
                                                        objectFit: "cover",
                                                    }}
                                                    width="40"
                                                />
                                                <div className="ml-4">
                                                    <div className="tracking-wide text-sm text-black dark:text-white font-semibold">
                                                        {
                                                            item?.supervisors
                                                                ?.name
                                                        }
                                                    </div>
                                                    <div className="text-gray-400 dark:text-gray-300">
                                                        {
                                                            item?.supervisors
                                                                ?.label
                                                        }
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="mt-4 text-gray-500 dark:text-gray-300">
                                            {item?.content}
                                        </div>
                                        <Separator className="my-4" />
                                    </div>
                                ))
                            ) : (
                                <></>
                            )}
                        </div>
                    </div>
                </Card>
            </div>
        </Layout>
    );
}
